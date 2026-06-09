<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Payable;
use App\Models\StudentPayable;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PayableAssignmentService
{
    public function assignForStudent(Student $student): void
    {
        $payables = Payable::where('school_year', $student->school_year)->get();

        foreach ($payables as $payable) {
            $this->assignSinglePayable($student, $payable);
        }
    }

    public function assignForStudents(Collection $students): void
    {
        if ($students->isEmpty()) {
            return;
        }

        $schoolYears = $students->pluck('school_year')->unique();

        $payables = Payable::whereIn('school_year', $schoolYears)
            ->get()
            ->groupBy('school_year');

        foreach ($students as $student) {
            $studentPayables = $payables->get($student->school_year, collect());
            foreach ($studentPayables as $payable) {
                $this->assignSinglePayable($student, $payable);
            }
        }
    }

    public function assignPayableToStudents(Payable $payable): void
    {
        // Only proceed if it's not repeatable (based on your current logic)
        if ($payable->is_repeatable) {
            return;
        }

        // Get all active students for this school year
        $students = Student::where('school_year', $payable->school_year)->get();

        # dd($students);

        if ($students->isEmpty()) {
            return;
        }

        foreach ($students as $student) {
            $this->assignSinglePayable($student, $payable);
        }
    }

    public function refreshUnpaidStudentPayables(Payable $payable): void
    {
        if ($payable->is_repeatable) {
            return;
        }

        // Step 1: Get unpaid records with their original names
        $unpaid = StudentPayable::where('payable_id', $payable->id)
            ->where('school_year', $payable->school_year)
            ->where('status', 'unpaid')
            ->get(['student_id', 'payable_name']);

        if ($unpaid->isEmpty()) {
            return;
        }

        // Step 2: Delete old unpaid records
        StudentPayable::where('payable_id', $payable->id)
            ->whereIn('student_id', $unpaid->pluck('student_id'))
            ->where('status', 'unpaid')
            ->delete();

        // Step 3: Re-assign with original name + new amount
        foreach ($unpaid as $item) {
            $student = Student::find($item->student_id);
            if (!$student) continue;

            $this->reassignWithOriginalName($student, $payable, $item->payable_name);
        }
    }

    private function reassignWithOriginalName(Student $student, Payable $payable, string $originalName): void
    {
        $details = $payable->details ?? [];
        $amount = $this->getAmountForStudentType($student, $details);

        $this->updateOrCreate($student, $payable, [
            'payable_name' => $originalName,     // Keep original name (e.g. Tuition - August 2026)
            'payable_type' => $payable->type,
            'amount'       => $amount,
            'total_amount' => $amount,
        ]);
    }

    private function getAmountForStudentType(Student $student, array $details): float
    {
        $studentTypes = $details['student_types'] ?? [];

        foreach ($studentTypes as $type) {
            if (($type['classification'] ?? '') === $student->classification) {
                return (float) $type['amount'];
            }
        }

        // Fallback
        return (float) ($details['amount'] ?? 0);
    }

    private function assignSinglePayable(Student $student, Payable $payable): void
    {
        if ($payable->school_year !== $student->school_year) {
            return;
        }

        if($payable->is_repeatable || $payable->type === 'uniforms') {
            return;
        }

        // Use 'type' instead of 'name' for better structure
        switch ($payable->type) {
            case 'tuition':
                $this->assignTuition($student, $payable);
                break;
            case 'uniforms':
                $this->assignUniform($student, $payable);
                break;
            case 'electricity':
                $this->assignElectricity($student, $payable);
                break;
            case 'assessment':
                $this->assignAssessment($student, $payable);
                break;
            case 'graduation':
                $this->assignGraduation($student, $payable);
                break;
            case 'enrollment':
                $this->assignEnrollment($student, $payable);
                break;
            case 'others':
                $this->assignOthers($student, $payable);
                break;
        }
    }

    # DONE [Don't Change]
    private function assignTuition(Student $student, Payable $payable): void
    {
        $details = $payable->details ?? [];

        $startMonth = $details['start_month'] ?? 'June';
        $endMonth   = $details['end_month']   ?? 'March';
        $dueDay     = (int) ($details['due_day'] ?? 25);

        $schoolYear = $payable->school_year;

        $months = $this->generateSchoolYearMonths($startMonth, $endMonth, $schoolYear, $dueDay);

        [$startYear, $endYear] = array_map('trim', explode('-', $schoolYear . '-'));

        foreach ($months as $monthName => $dueDate) {
            $monthNum = date('n', strtotime($monthName));

            $year = ($monthNum >= 1 && $monthNum <= 5) ? $endYear : $startYear;

            $payableName = ucfirst($payable->name) . " - " . $monthName . " " . $year;

            $amount = $this->getAmountForStudentType($student, $details);

            $this->updateOrCreate($student, $payable, [
                'payable_name' => $payableName,
                'payable_type' => $payable->type,
                'amount'       => $amount,
                'total_amount' => $amount,
                'due_date'     => $dueDate,
            ]);
        }
    }

    # DONE [Don't Change]
    private function assignElectricity(Student $student, Payable $payable): void
    {
        $details = $payable->details ?? [];

        $startMonth = $details['start_month'] ?? 'June';
        $endMonth   = $details['end_month']   ?? 'March';
        $dueDay     = (int) ($details['due_day'] ?? 25);

        $schoolYear = $payable->school_year; // e.g., "2026-2027"

        $months = $this->generateSchoolYearMonths($startMonth, $endMonth, $schoolYear, $dueDay);

        // Parse school year
        [$startYear, $endYear] = array_map('trim', explode('-', $schoolYear));

        foreach ($months as $monthName => $dueDate) {
            $monthNum = date('n', strtotime($monthName));

            // June–December → startYear, January–May → endYear
            $year = ($monthNum >= 1 && $monthNum <= 5) ? $endYear : $startYear;

            $payableName = ucfirst($payable->name) . " - " . $monthName . " " . $year;

            $amount = $this->getAmountForStudentType($student, $details) ?? ($details['amount'] ?? 0);

            $this->updateOrCreate($student, $payable, [
                'payable_name' => $payableName,
                'payable_type' => $payable->type,
                'amount'       => $amount,
                'total_amount' => $amount,
                'due_date'     => $dueDate,
            ]);
        }
    }

    # DONE [Don't Change]
    private function assignUniform(Student $student, Payable $payable): void
    {
        $config = $this->getUniformConfig($payable->details ?? []);

        $uniformSex = strtolower($config['sex']);
        $studentSex = strtolower($student->sex ?? '');

        if ($uniformSex !== 'unisex' && $uniformSex !== $studentSex) {
            return;
        }

        $amount = $this->getUniformAmount($config);

        $this->updateOrCreate($student, $payable, [
            'payable_name' => $payable->name,
            'payable_type' => $payable->type,
            'amount'       => $amount,
            'total_amount' => $amount,
            'due_date'     => $config['due_date'],
            'remarks'      => "Uniform (" . ucfirst($config['sex']) . ")",
        ]);
    }

    # DONE [Don't Change]
    private function assignAssessment(Student $student, Payable $payable): void
    {
        $details = $payable->details ?? [];

        $applicableTo = $details['applicable_to'] ?? [];

        if (
            !in_array('All Strands', $applicableTo) &&
            !in_array($student->cluster, $applicableTo)
        ) {
            return;
        }

        // Compute total assessment amount
        $amount = collect($details['particulars'] ?? [])
            ->sum(function ($particular) {
                return (float) array_values($particular)[0];
            });

        $this->updateOrCreate($student, $payable, [
            'payable_name' => $payable->name,
            'payable_type' => $payable->type,
            'amount'       => $amount,
            'total_amount' => $amount,
            'remarks'      => 'Assessment Fee',
        ]);
    }

    # DONE [Don't Change]
    private function assignEnrollment(Student $student, Payable $payable): void
    {
        $details = $payable->details ?? [];

        $amount = (float) ($details['amount'] ?? 0);

        $this->updateOrCreate($student, $payable, [
            'payable_name' => $payable->name,
            'payable_type' => $payable->type,
            'amount'       => $amount,
            'total_amount' => $amount,
            'paid_amount'  => $amount,
            'status'       => 'paid',
            'remarks'      => 'Enrollment Fee',
        ]);
    }

    # DONE [Don't Change]
    private function assignGraduation(Student $student, Payable $payable): void
    {
        $details = $payable->details ?? [];

        $requiredYearLevel = $details['year_level'] ?? null;

        if (
            $requiredYearLevel &&
            $student->grade_level !== $requiredYearLevel
        ) {
            return;
        }

        $amount = (float) ($details['amount'] ?? 0);

        $this->updateOrCreate($student, $payable, [
            'payable_name' => $payable->name,
            'payable_type' => $payable->type,
            'amount'       => $amount,
            'total_amount' => $amount,
            'due_date'     => $details['due_date'] ?? null,
            'remarks'      => 'Graduation Fee',
        ]);
    }

    # DONE [Don't Change]
    private function assignOthers(Student $student, Payable $payable): void
    {
        $details = $payable->details ?? [];

        $amount = (float) ($details['amount'] ?? 0);

        $this->updateOrCreate($student, $payable, [
            'payable_name' => $payable->name,
            'payable_type' => $payable->type,
            'amount'       => $amount,
            'total_amount' => $amount,
            'due_date'     => $details['due_date'] ?? null,
            'remarks'      => 'Other Fee',
        ]);
    }

    private function getUniformAmount(array $config): float
    {
        $sizes = $config['sizes'] ?? [];

        if (empty($sizes)) {
            return (float) ($config['amount'] ?? 0);
        }

        // Return the smallest amount as initial amount
        return (float) collect($sizes)->min('amount');
    }

    private function getUniformConfig(array $config): array
    {
        return [
            'sex'   => $config['sex'] ?? 'Unisex',
            'sizes' => $config['sizes'] ?? [],
            'due_date' => $config['due_date'] ?? null,
        ];
    }

    private function updateOrCreate(Student $student, Payable $payable, array $data): void
    {
        $data['payable_type'] = $data['payable_type'] ?? $payable->type ?? $payable->name;

        StudentPayable::updateOrCreate(
            [
                'student_id'   => $student->id,
                'payable_id'   => $payable->id,
                'school_year'  => $student->school_year,
                'payable_name' => $data['payable_name'],
            ],
            array_merge([
                'grade_level'    => $student->grade_level,
                'penalty_amount' => 0,
                'paid_amount'    => 0,
                'status'         => 'unpaid',
            ], $data)
        );
    }

    # DONE [Don't Change]
    private function generateSchoolYearMonths(string $startMonth, string $endMonth, string $schoolYear, int $dueDay = 25): array
    {
        $startYear = (int) substr($schoolYear, 0, 4);
        $endYear   = $startYear + 1;

        $months = [];
        $current = Carbon::create($startYear, $this->getMonthNumber($startMonth), 1);
        $endDate = Carbon::create($endYear, $this->getMonthNumber($endMonth), 28);

        while ($current->lte($endDate)) {
            $monthName = $current->format('F');
            $dueDate   = $current->copy()->day(min($dueDay, $current->daysInMonth))->format('Y-m-d');

            $months[$monthName] = $dueDate;
            $current->addMonthNoOverflow();
        }

        return $months;
    }

    # DONE [Don't Change]
    private function getMonthNumber(string $monthName): int
    {
        return Carbon::parse("1 {$monthName} 2000")->month;
    }
}
