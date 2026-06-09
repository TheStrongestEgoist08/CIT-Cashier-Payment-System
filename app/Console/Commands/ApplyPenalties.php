<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentPayable;
use App\Models\Penalty;
use Carbon\Carbon;

class ApplyPenalties extends Command
{
    protected $signature = 'penalties:apply';
    protected $description = 'Apply fixed penalties to overdue student payables';

    public function handle()
    {
        $this->info('Starting penalty application...');

        $now = Carbon::now();
        $appliedCount = 0;

        // Get all overdue unpaid payables
        $overduePayables = StudentPayable::where('status', 'unpaid')
            ->whereNotNull('due_date')
            ->where('due_date', '<', $now->toDateString())
            ->get();

        // Load penalties by type
        $penalties = Penalty::all()->keyBy('type');

        foreach ($overduePayables as $payable) {
            // Prevent applying penalty multiple times in the same day
            if ($payable->updated_at->isToday() && $payable->penalty_amount > 0) {
                continue;
            }

            $penaltyRule = $penalties->get($payable->payable_type);

            if (!$penaltyRule) {
                continue; // No penalty defined for this type
            }

            $penaltyAmount = $penaltyRule->amount;

            if ($penaltyAmount > 0) {
                $payable->penalty_amount += $penaltyAmount;
                $payable->total_amount = $payable->amount + $payable->penalty_amount;
                $payable->save();

                $appliedCount++;

                $this->line("Applied ₱" . number_format((float) $penaltyAmount, 2) .
                           " penalty to {$payable->payable_name} (Student ID: {$payable->student_id})");
            }
        }

        $this->info("Penalty application completed. {$appliedCount} penalties applied.");
    }
}
