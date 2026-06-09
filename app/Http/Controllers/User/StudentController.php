<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Services\PayableAssignmentService;
use App\Models\Transaction;
use App\Models\StudentPayable;
use App\Models\Payable;

class StudentController extends Controller
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

        // Dynamic dropdown data
        $sections = Student::whereNotNull('section')
            ->distinct()
            ->pluck('section')
            ->sort()
            ->values();

        $schoolYears = Student::whereNotNull('school_year')
            ->distinct()
            ->pluck('school_year')
            ->sortDesc()
            ->values();

        return view('students.index', compact('students', 'sections', 'schoolYears'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $studentsImported = collect();

            $currentYear = (int) date('Y');
            # $fallbackSchoolYear = $currentYear . '-' . ($currentYear + 1);
            $fallbackSchoolYear = '2025-2026';
            $fallbackGradeLevel = 'Grade 11';

            (new FastExcel)->import($request->file('file'), function ($line) use (&$studentsImported, $fallbackSchoolYear, $fallbackGradeLevel) {

                $student_id = trim($line['STUDENT NUMBER'] ?? $line['Student Number'] ?? $line['student_number'] ?? '');
                $lrn        = trim($line['LRN'] ?? '');

                if (empty($student_id) || empty($lrn)) {
                    return;
                }

                // ==================== CLASSIFICATION ====================
                $rawClass = trim($line['CLASSIFICATION'] ?? $line['Classification'] ?? '');
                $classification = match(strtolower($rawClass)) {
                    'regular payee', 'regular', 'payee' => 'Regular Payee',
                    'esc', 'esc grantee', 'grantee'     => 'ESC Grantee',
                    'voucher beneficiary', 'voucher', 'beneficiary' => 'Voucher Beneficiary',
                    default => 'Regular Payee',
                };

                // ==================== GRADE LEVEL ====================
                $year1 = trim($line['YEAR 1'] ?? $line['Year 1'] ?? $line['year1'] ?? $line['Grade'] ?? '');
                $year2 = trim($line['YEAR 2'] ?? $line['Year 2'] ?? $line['year2'] ?? '');
                $gradeRaw = $year2 ?: $year1;

                $grade_level = match(strtolower($gradeRaw)) {
                    '11', 'grade 11', 'g11', '11th' => 'Grade 11',
                    '12', 'grade 12', 'g12', '12th' => 'Grade 12',
                    default => $fallbackGradeLevel,
                };

                // Cluster & Section
                $section = $line['SECTION 2'] ?: ($line['SECTION 1'] ?? null);
                $cluster = !empty($line['CLUSTER 2']) ? $line['CLUSTER 2'] : ($line['CLUSTER 1'] ?? null);

                // School Year
                $schoolYear = trim($line['CURRENT SCHOOL YEAR'] ?? $line['School Year'] ?? $line['school_year'] ?? '');
                if (empty($schoolYear)) {
                    $schoolYear = $fallbackSchoolYear;
                }

                $student = Student::updateOrCreate(
                    ['student_id' => $student_id],
                    [
                        'LRN'            => $lrn,
                        'complete_name'  => $line['Complete name 1'] ?? $line['Complete Name 1'] ?? $line['Name'] ?? null,
                        'sex'            => $line['Sex'] ?? $line['sex'] ?? null,
                        'grade_level'    => $grade_level,
                        'school_year'    => $schoolYear,
                        'section'        => $section,
                        'cluster'        => $cluster,
                        'classification' => $classification,
                    ]
                );

                $studentsImported->push($student);
            });

            if ($studentsImported->isNotEmpty()) {
                $service = app(PayableAssignmentService::class);
                $service->assignForStudents($studentsImported);
            }

            $message = $studentsImported->count() > 0
                ? $studentsImported->count() . ' students imported and payables assigned successfully!'
                : 'No valid students found to import.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function getPayables(Student $student)
    {
        $student->load('studentPayables');

        // === NON-REPEATABLE (Existing Student Payables) ===
        $nonRepeatablePayables = $student->studentPayables
            ->where('is_repeatable', false)  // Only non-repeatables
            ->map(function ($sp) {
                return [
                    'id'                => $sp->id,
                    'payable_id'        => $sp->payable_id,
                    'payable_name'      => $sp->payable_name,
                    'payable_type'      => $sp->payable_type,
                    'school_year'       => $sp->school_year,
                    'grade_level'       => $sp->grade_level,
                    'amount'            => $sp->amount,
                    'penalty_amount'    => $sp->penalty_amount,
                    'total_amount'      => $sp->total_amount,
                    'paid_amount'       => $sp->paid_amount,
                    'status'            => $sp->status,
                    'remarks'           => $sp->remarks,
                    'is_repeatable'     => false,
                    'details'           => null,
                ];
            })->values();

        // === REPEATABLE Payables (for new purchases) ===
        $repeatablePayables = Payable::where('is_repeatable', true)
            ->get()
            ->map(function ($payable) use ($student) {
                return [
                    'id'                => null,
                    'payable_id'        => $payable->id,
                    'payable_name'      => $payable->name,
                    'payable_type'      => $payable->type,
                    'school_year'       => $payable->school_year ?? $student->school_year,
                    'grade_level'       => $student->grade_level,
                    'amount'            => 0,
                    'penalty_amount'    => 0,
                    'total_amount'      => 0,
                    'paid_amount'       => 0,
                    'status'            => 'pending',
                    'is_repeatable'     => true,
                    'details'           => $payable->details,
                ];
            })->values();

        return response()->json([
            'student'              => $student,
            'non_repeatables'      => $nonRepeatablePayables,
            'repeatables'          => $repeatablePayables,
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->expectsJson() && !$request->ajax()) {
            abort(400, 'Bad Request');
        }

        $request->validate([
            'student_id'        => 'required|exists:students,id',
            'selected_payables' => 'required|array|min:1',
            'remarks'           => 'nullable|string|max:500',
            'or_number'         => 'nullable|string|max:50',
        ]);

        try {
            $student = Student::findOrFail($request->student_id);

            $selectedPayables = $request->selected_payables;
            $orNumber = $request->or_number;

            $totalAmount = 0;
            $totalPenalty = 0;
            $payablesData = [];

            foreach ($selectedPayables as $item) {

                $chargeAmount = (float) ($item['charge_amount'] ?? 0);
                $quantity     = (int) ($item['quantity'] ?? 1);
                $size         = $item['size'] ?? null;
                $isExempted   = filter_var($item['is_exempted'] ?? false, FILTER_VALIDATE_BOOLEAN);

                $orFromItem   = $item['OR'] ?? $orNumber;

                $payableName  = $item['payable_name'];
                $payableType  = $item['payable_type'];
                $schoolYear   = $item['school_year'] ?? $student->school_year;

                // Add size to uniform names
                if ($payableType && strtolower($payableType) === 'uniforms' && $size) {
                    $payableName = "{$payableName} - Size {$size}";
                }

                /*
                |--------------------------------------------------------------------------
                | BIGAY / EXEMPTED
                |--------------------------------------------------------------------------
                */
                if ($isExempted) {

                    if (!empty($item['student_payable_id'])) {

                        $studentPayable = StudentPayable::findOrFail(
                            $item['student_payable_id']
                        );

                        $studentPayable->update([
                            'amount'         => 0,
                            'total_amount'   => 0,
                            'paid_amount'    => 0,
                            'penalty_amount' => 0,
                            'status'         => 'exempted',
                            'remarks'        => 'bigay',
                        ]);

                    } else {

                        StudentPayable::create([
                            'student_id'     => $student->id,
                            'payable_id'     => $item['payable_id'],
                            'payable_name'   => $payableName,
                            'payable_type'   => $payableType,
                            'grade_level'    => $student->grade_level,
                            'school_year'    => $schoolYear,
                            'amount'         => 0,
                            'total_amount'   => 0,
                            'paid_amount'    => 0,
                            'penalty_amount' => 0,
                            'due_date'       => now()->addDays(30),
                            'status'         => 'exempted',
                            'remarks'        => 'bigay',
                        ]);
                    }

                    // Skip transaction creation
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | EXISTING PAYABLE
                |--------------------------------------------------------------------------
                */
                if (!empty($item['student_payable_id'])) {

                    $studentPayable = StudentPayable::findOrFail(
                        $item['student_payable_id']
                    );

                    $penaltyAmount = (float) ($studentPayable->penalty_amount ?? 0);

                    $studentPayable->paid_amount =
                        ($studentPayable->paid_amount ?? 0) + $chargeAmount;

                    if ($orFromItem) {
                        $studentPayable->OR = $orFromItem;
                        $studentPayable->remarks = trim(
                            ($studentPayable->remarks ?? '') . " | OR: {$orFromItem}"
                        );
                    }

                    if (
                        $studentPayable->paid_amount >=
                        $studentPayable->total_amount
                    ) {
                        $studentPayable->status = 'paid';
                    }

                    $studentPayable->save();

                    $payablesData[] = [
                        'student_payable_id' => $studentPayable->id,
                        'payable_id'         => $studentPayable->payable_id,
                        'payable_name'       => $payableName,
                        'payable_type'       => $payableType,
                        'school_year'        => $schoolYear,
                        'amount'             => $chargeAmount,
                        'penalty_amount'     => $penaltyAmount,
                        'quantity'           => 1,
                        'size'               => null,
                        'OR'                 => $orFromItem,
                        'total'              => $chargeAmount + $penaltyAmount,
                    ];

                    $totalAmount += $chargeAmount;
                    $totalPenalty += $penaltyAmount;

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | NEW REPEATABLE PURCHASE
                    |--------------------------------------------------------------------------
                    */

                    $baseAmount = $chargeAmount / max($quantity, 1);

                    $studentPayable = StudentPayable::create([
                        'student_id'     => $student->id,
                        'payable_id'     => $item['payable_id'],
                        'payable_name'   => $payableName,
                        'payable_type'   => $payableType,
                        'grade_level'    => $student->grade_level,
                        'school_year'    => $schoolYear,
                        'amount'         => $baseAmount,
                        'total_amount'   => $chargeAmount,
                        'paid_amount'    => $chargeAmount,
                        'penalty_amount' => 0,
                        'due_date'       => now()->addDays(30),
                        'status'         => 'paid',
                        'OR'             => $orFromItem,
                        'remarks'        => $size
                            ? "Size: {$size} | Qty: {$quantity} | OR: {$orFromItem}"
                            : "Qty: {$quantity} | OR: {$orFromItem}",
                    ]);

                    $payablesData[] = [
                        'student_payable_id' => $studentPayable->id,
                        'payable_id'         => $item['payable_id'],
                        'payable_name'       => $payableName,
                        'payable_type'       => $payableType,
                        'school_year'        => $schoolYear,
                        'amount'             => $baseAmount,
                        'penalty_amount'     => 0,
                        'quantity'           => $quantity,
                        'size'               => $size,
                        'OR'                 => $orFromItem,
                        'total'              => $chargeAmount,
                    ];

                    $totalAmount += $chargeAmount;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | NO TRANSACTION NEEDED (ALL BIGAY)
            |--------------------------------------------------------------------------
            */
            if (empty($payablesData)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Selected payables were marked as Bigay.',
                ]);
            }

            $transactionCode =
                'TRX-' .
                date('Ymd') .
                '-' .
                str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'student_id'       => $student->id,
                'transaction_code' => $transactionCode,
                'total_amount'     => $totalAmount,
                'total_penalty'    => $totalPenalty,
                'payables'         => $payablesData,
                'remarks'          => $orNumber ? 'OR: ' . $orNumber : null,
                'created_by'       => auth()->id(),
            ]);

            return response()->json([
                'success'          => true,
                'message'          => 'Payment recorded successfully.',
                'transaction_code' => $transactionCode,
                'transaction_id'   => $transaction->id
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 422);
        }
    }
}
