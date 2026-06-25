<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SOAController extends Controller
{
    public function index(Request $request)
    {
        $schoolYear = $request->school_year;
        $gradeLevel = $request->grade_level;
        $section = $request->section;
        $search = $request->search;

        $query = Student::query();

        if ($schoolYear) {
            $query->where('school_year', $schoolYear);
        }

        if ($gradeLevel) {
            $query->where('grade_level', $gradeLevel);
        }

        if ($section) {
            $query->where('section', $section);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('complete_name', 'like', "%{$search}%")
                ->orWhere('student_id', 'like', "%{$search}%")
                ->orWhere('lrn', 'like', "%{$search}%");
            });
        }

        $students = $query
            ->with('studentPayables.payable')
            ->latest()
            ->paginate(50)
            ->withQueryString();

        $schoolYears = Student::select('school_year')
            ->distinct()
            ->orderBy('school_year')
            ->pluck('school_year');

        $sections = Student::select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        return view('summary.index', compact(
            'students',
            'schoolYears',
            'sections'
        ));
    }

    public function exportStudentSOA(Request $request)
    {
        if (!$request->filled('student_ids') || empty($request->student_ids)) {
            return redirect()
                ->route('admin.summary')
                ->with('warning', 'No students selected for export.');
        }

        $studentIds = $request->input('student_ids');
        $monthInput = $request->input('month');

        if (!$monthInput) {
            return redirect()
                ->route('admin.summary')
                ->with('warning', 'Please select a month.');
        }

        try {
            $selectedMonth = Carbon::parse($monthInput)->endOfMonth();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Invalid month format.');
        }

        // Fetch students with their payables
        $students = Student::with(['studentPayables' => function ($query) use ($selectedMonth) {
            $query->where('status', '!=', 'paid')
                ->whereDate('due_date', '<=', $selectedMonth)
                ->orderBy('due_date', 'asc');
        }])
        ->whereIn('id', $studentIds)
        ->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('warning', 'No students found.');
        }

        $preparedBy = Auth::user()->name ?? Auth::user()->username ?? 'Cashier / Registrar';

        $allStudentsData = [];

        foreach ($students as $student) {
            $payables = [];

            foreach ($student->studentPayables as $payable) {
                $balance = $payable->total_amount - $payable->paid_amount;

                if ($balance > 0) {
                    $payables[] = [
                        'payable_name'   => $payable->payable_name,
                        'amount'         => (float) $payable->amount,           // Added
                        'penalty_amount' => (float) $payable->penalty_amount,   // Added
                        'total_amount'   => (float) $payable->total_amount,
                        'paid_amount'    => (float) $payable->paid_amount,      // kept for future use
                        'balance'        => (float) $balance,
                    ];
                }
            }

            $totalBalance = collect($payables)->sum('balance');

            // Only include student if they have outstanding balance
            if ($totalBalance > 0) {
                $allStudentsData[] = [
                    'student'       => $student,
                    'payables'      => $payables,
                    'total_balance' => $totalBalance,
                ];
            }
        }

        $data = [
            'studentsData' => $allStudentsData,
            'filterMonth'  => $selectedMonth->format('Y-m'),
            'preparedBy'   => $preparedBy,
        ];

        $pdf = Pdf::loadView('summary.partials.statement-pdf', $data)
            ->setPaper('Legal', 'portrait')
            ->setOption('margin-top', 8)
            ->setOption('margin-bottom', 8)
            ->setOption('margin-left', 8)
            ->setOption('margin-right', 8);

        $monthName = $selectedMonth->format('F Y');
        $filename = "SOA_{$monthName}_" . now()->format('YmdHis') . ".pdf";

        return $pdf->download($filename);
    }

    public function printStudentSOA(Request $request)
    {
        if (!$request->filled('student_ids') || empty($request->student_ids)) {
            return redirect()->route('admin.summary')->with('warning', 'No students selected.');
        }

        $studentIds = $request->input('student_ids');
        $monthInput = $request->input('month');

        if (!$monthInput) {
            return redirect()->route('admin.summary')->with('warning', 'Please select a month.');
        }

        try {
            $selectedMonth = Carbon::parse($monthInput)->endOfMonth();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid month format.');
        }

        $students = Student::with(['studentPayables' => function ($query) use ($selectedMonth) {
            $query->where('status', '!=', 'paid')
                ->whereDate('due_date', '<=', $selectedMonth)
                ->orderBy('due_date', 'asc');
        }])
        ->whereIn('id', $studentIds)
        ->get();

        $preparedBy = Auth::user()->name ?? Auth::user()->username ?? 'Cashier / Registrar';

        $allStudentsData = [];

        foreach ($students as $student) {
            $payables = [];

            foreach ($student->studentPayables as $payable) {
                $balance = $payable->total_amount - $payable->paid_amount;

                if ($balance > 0) {
                    $payables[] = [
                        'payable_name'   => $payable->payable_name,
                        'amount'         => (float) $payable->amount,           // Added
                        'penalty_amount' => (float) $payable->penalty_amount,   // Added
                        'total_amount'   => (float) $payable->total_amount,
                        'paid_amount'    => (float) $payable->paid_amount,
                        'balance'        => (float) $balance,
                    ];
                }
            }

            $totalBalance = collect($payables)->sum('balance');

            if ($totalBalance > 0) {
                $allStudentsData[] = [
                    'student'       => $student,
                    'payables'      => $payables,
                    'total_balance' => $totalBalance,
                ];
            }
        }

        $data = [
            'studentsData' => $allStudentsData,
            'filterMonth'  => $selectedMonth->format('Y-m'),
            'preparedBy'   => $preparedBy,
        ];

        $pdf = Pdf::loadView('summary.partials.statement-pdf', $data)
            ->setPaper('Legal', 'portrait')
            ->setOption('margin-top', 8)
            ->setOption('margin-bottom', 8)
            ->setOption('margin-left', 8)
            ->setOption('margin-right', 8);

        return $pdf->stream('SOA_' . $selectedMonth->format('F_Y') . '.pdf');
    }

    public function exportStudentSummaryOfAccount(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (empty($studentIds)) {
            return redirect()
                ->route('admin.summary')
                ->with('warning', 'No students selected.');
        }

        $students = Student::with([
            'studentPayables' => function ($query) {
                $query->orderBy('grade_level')
                    ->orderBy('school_year', 'desc')
                    ->orderBy('due_date', 'asc');
            }
        ])
        ->whereIn('id', $studentIds)
        ->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('warning', 'No students found.');
        }

        $soas = [];
        $preparedBy = Auth::user()->name
            ?? Auth::user()->username
            ?? 'Cashier / Registrar';

        foreach ($students as $student) {
            $groupedData = [];

            foreach ($student->studentPayables as $payable) {
                $gradeLevel = $payable->grade_level ?? 'Unknown Grade';
                $schoolYear = $payable->school_year ?? 'Unknown School Year';

                // Initialize grouping structure
                if (!isset($groupedData[$gradeLevel])) {
                    $groupedData[$gradeLevel] = [];
                }
                if (!isset($groupedData[$gradeLevel][$schoolYear])) {
                    $groupedData[$gradeLevel][$schoolYear] = [
                        'paid'       => [],
                        'unpaid'     => [],
                        'exempted'   => [],
                        'paidTotal'   => 0,
                        'unpaidTotal' => 0,
                        'exemptedTotal' => 0,
                    ];
                }

                $balance = $payable->total_amount - $payable->paid_amount;

                $item = [
                    'payable_name'   => $payable->payable_name,
                    'OR'             => $payable->OR ?? '-',
                    'amount'         => (float) $payable->amount,
                    'penalty_amount' => (float) $payable->penalty_amount,
                    'paid_amount'    => (float) $payable->paid_amount,
                    'total_amount'   => (float) $payable->total_amount,
                    'balance'        => (float) $balance,
                    'status'         => $payable->status,
                    'remarks'        => $payable->remarks ?? 'Exempted',   // You can change this
                ];

                // Determine category
                if ($payable->status === 'exempted' || ($payable->is_exempted ?? false)) {
                    $groupedData[$gradeLevel][$schoolYear]['exempted'][] = $item;
                    $groupedData[$gradeLevel][$schoolYear]['exemptedTotal'] += $payable->total_amount;
                } elseif ($balance <= 0) {
                    $groupedData[$gradeLevel][$schoolYear]['paid'][] = $item;
                    $groupedData[$gradeLevel][$schoolYear]['paidTotal'] += $payable->total_amount;
                } else {
                    $groupedData[$gradeLevel][$schoolYear]['unpaid'][] = $item;
                    $groupedData[$gradeLevel][$schoolYear]['unpaidTotal'] += $balance;
                }
            }

            $soas[] = [
                'student'      => $student,
                'groupedData'  => $groupedData,
                'generatedAt'  => now(),
                'preparedBy'   => $preparedBy,
            ];
        }

        $pdf = Pdf::loadView('summary.partials.summary-pdf', [
            'soas' => $soas
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isFontSubsettingEnabled', true);
        $pdf->setOption('defaultFont', 'Times New Roman');

        $filename = 'Summary_of_Account_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    public function printStudentSummaryOfAccount(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (empty($studentIds)) {
            return redirect()->route('admin.summary')
                ->with('warning', 'No students selected.');
        }

        $students = Student::with([
            'studentPayables' => function ($query) {
                $query->orderBy('grade_level')
                    ->orderBy('school_year', 'desc')
                    ->orderBy('due_date');
            }
        ])
        ->whereIn('id', $studentIds)
        ->get();

        $preparedBy = Auth::user()->name
            ?? Auth::user()->username
            ?? 'Cashier / Registrar';

        $soas = [];

        foreach ($students as $student) {
            $groupedData = [];

            foreach ($student->studentPayables as $payable) {
                $gradeLevel = $payable->grade_level ?? 'Unknown Grade';
                $schoolYear = $payable->school_year ?? 'Unknown School Year';

                // Initialize structure
                if (!isset($groupedData[$gradeLevel])) {
                    $groupedData[$gradeLevel] = [];
                }
                if (!isset($groupedData[$gradeLevel][$schoolYear])) {
                    $groupedData[$gradeLevel][$schoolYear] = [
                        'paid'          => [],
                        'unpaid'        => [],
                        'exempted'      => [],
                        'paidTotal'     => 0,
                        'unpaidTotal'   => 0,
                        'exemptedTotal' => 0,
                    ];
                }

                $balance = $payable->total_amount - $payable->paid_amount;

                $item = [
                    'payable_name'   => $payable->payable_name,
                    'OR'             => $payable->OR ?? '-',
                    'amount'         => (float) $payable->amount,
                    'penalty_amount' => (float) $payable->penalty_amount,
                    'paid_amount'    => (float) $payable->paid_amount,
                    'total_amount'   => (float) $payable->total_amount,
                    'balance'        => (float) $balance,
                    'status'         => $payable->status,
                    'remarks'        => $payable->remarks ?? 'Exempted',
                ];

                // Categorize the payable
                if ($payable->status === 'exempted' || ($payable->is_exempted ?? false)) {
                    $groupedData[$gradeLevel][$schoolYear]['exempted'][] = $item;
                    $groupedData[$gradeLevel][$schoolYear]['exemptedTotal'] += $payable->total_amount;
                } elseif ($balance <= 0) {
                    $groupedData[$gradeLevel][$schoolYear]['paid'][] = $item;
                    $groupedData[$gradeLevel][$schoolYear]['paidTotal'] += $payable->total_amount;
                } else {
                    $groupedData[$gradeLevel][$schoolYear]['unpaid'][] = $item;
                    $groupedData[$gradeLevel][$schoolYear]['unpaidTotal'] += $balance;
                }
            }

            $soas[] = [
                'student'      => $student,
                'groupedData'  => $groupedData,
                'generatedAt'  => now(),
                'preparedBy'   => $preparedBy,
            ];
        }

        $pdf = Pdf::loadView('summary.partials.summary-pdf', [
            'soas' => $soas
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('defaultFont', 'Times New Roman');

        return $pdf->stream('Summary_of_Account_' . now()->format('Y-m-d_His') . '.pdf');
    }
}
