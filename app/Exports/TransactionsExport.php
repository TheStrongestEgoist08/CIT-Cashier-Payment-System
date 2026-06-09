<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $period;
    protected $search;
    protected $overallTotal = 0;

    public function __construct($period, $search = null)
    {
        $this->period = $period;
        $this->search = $search;
    }

    public function collection()
    {
        $query = Transaction::with('student', 'createdBy');

        match ($this->period) {
            'today'      => $query->whereDate('created_at', Carbon::today()),
            'this_week'  => $query->where('created_at', '>=', Carbon::now()->startOfWeek()),
            'this_month' => $query->where('created_at', '>=', Carbon::now()->startOfMonth()),
            'this_year'  => $query->where('created_at', '>=', Carbon::now()->startOfYear()),
            default      => $query->where('created_at', '>=', Carbon::now()->startOfMonth()),
        };

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('transaction_code', 'like', "%{$this->search}%")
                  ->orWhereHas('student', function ($s) {
                      $s->where('complete_name', 'like', "%{$this->search}%")
                        ->orWhere('student_id', 'like', "%{$this->search}%");
                  });
            });
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'DATE',
            'Name',
            'Payables',
            'amount',
            'penalty',
            'total_amount',
            'Recorded by'
        ];
    }

    public function map($transaction): array
    {
        $rows = [];
        $first = true;
        $recordedBy = $transaction->createdBy->name ?? 'N/A';
        $transactionTotal = $transaction->total_amount + $transaction->total_penalty;
        $this->overallTotal += $transactionTotal;

        foreach ($transaction->payables as $item) {
            $itemTotal = ($item['amount'] ?? 0) + ($item['penalty_amount'] ?? 0);

            $rows[] = [
                $first ? $transaction->created_at->format('M d, Y') : '',
                $first ? ($transaction->student->complete_name ?? 'N/A') : '',
                $item['payable_name'] ?? 'N/A',
                $item['amount'] ?? 0,
                $item['penalty_amount'] ?? 0,
                $itemTotal,
                $first ? $recordedBy : '',
            ];

            $first = false;
        }

        // Transaction Total Row
        if (!empty($transaction->payables)) {
            $rows[] = [
                '',
                '',
                'Transaction Total',
                '',
                '',
                $transactionTotal,
                '',
            ];
        }

        if (empty($transaction->payables)) {
            $rows[] = [
                $transaction->created_at->format('M d, Y'),
                $transaction->student->complete_name ?? 'N/A',
                'No Items',
                0,
                0,
                $transactionTotal,
                $recordedBy,
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F1F5F9']],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell('C' . $row)->getValue() === 'Transaction Total') {
                        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DBEAFE']],
                        ]);
                    }
                }

                $lastRow = $highestRow + 2;
                $sheet->setCellValue('A' . $lastRow, 'FINAL GRAND TOTAL');
                $sheet->setCellValue('F' . $lastRow, $this->overallTotal);

                $sheet->getStyle('A' . $lastRow . ':G' . $lastRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E40AF']],
                ]);

                $sheet->getStyle('D' . '2:' . 'F' . $highestRow)->getAlignment()->setHorizontal('right');
            },
        ];
    }
}
