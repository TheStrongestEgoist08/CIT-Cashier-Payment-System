<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('student', 'createdBy');

        $period = $request->input('period', 'this_month');

        // Period Filter
        match ($period) {
            'today'      => $query->whereDate('created_at', Carbon::today()),
            'this_week'  => $query->where('created_at', '>=', Carbon::now()->startOfWeek()),
            'this_month' => $query->where('created_at', '>=', Carbon::now()->startOfMonth()),
            'this_year'  => $query->where('created_at', '>=', Carbon::now()->startOfYear()),
            default      => $query->where('created_at', '>=', Carbon::now()->startOfMonth()),
        };

        // Search Filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($s) use ($search) {
                      $s->where('complete_name', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->paginate(50)->withQueryString();

        // Summary Statistics
        $summary = [
            'total_transactions' => $query->count(),
            'total_amount'       => $query->sum('total_amount') - $query->sum('total_penalty'),
            'total_penalty'      => $query->sum('total_penalty'),
            'grand_total'        => $query->sum('total_amount'),
        ];

        return view('reports.index', compact('transactions', 'summary', 'period'));
    }

    public function exportExcel(Request $request)
    {
        $period = $request->input('period', 'this_month');
        $search = $request->input('search');

        return Excel::download(
            new TransactionsExport($period, $search),
            'transactions_report_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }
}
