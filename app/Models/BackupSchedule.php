<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BackupSchedule extends Model
{
    protected $table = 'backup_schedules';

    protected $fillable = [
        'is_enabled',
        'frequency',
        'backup_time',
        'day_of_week',
        'day_of_month',
        'backup_path_id',
        'last_run_at',
        'next_run_at',
    ];

    protected $casts = [
        'is_enabled'     => 'boolean',
        'backup_time'    => 'datetime:H:i',
        'last_run_at'    => 'datetime',
        'next_run_at'    => 'datetime',
        'day_of_month'   => 'integer',
    ];

    public function backupPath()
    {
        return $this->belongsTo(BackupPath::class);
    }

    public static function getSettings(): self
    {
        $defaultPath = BackupPath::where('is_default', true)->first();

        return self::firstOrCreate(
            [],
            [
                'is_enabled'     => false,
                'frequency'      => 'daily',
                'backup_time'    => '02:00:00',
                'day_of_week'    => 'sun',
                'day_of_month'   => 1,
                'backup_path_id' => $defaultPath?->id,
            ]
        );
    }

    public function getFrequencyLabelAttribute(): string
    {
        return match($this->frequency) {
            'daily'     => 'Daily',
            'weekly'    => 'Once a week',
            'biweekly'  => 'Every second week',
            'monthly'   => 'Monthly',
            'quarterly' => 'Quarterly',
            'yearly'    => 'Yearly',
            default     => ucfirst($this->frequency),
        };
    }

    public function updateNextRun()
    {
        if (!$this->is_enabled) {
            $this->next_run_at = null;
            $this->save();
            return;
        }

        $now = Carbon::now();
        $time = Carbon::parse($this->backup_time);

        $next = match($this->frequency) {
            'daily'     => $now->copy()->setTimeFrom($time),
            'weekly',
            'biweekly'  => $this->getNextWeeklyRun($time),
            'monthly',
            'quarterly',
            'yearly'    => $this->getNextMonthlyRun($time),
            default     => $now->copy()->addDay()->setTimeFrom($time),
        };

        // If the calculated time is in the past, move to next occurrence
        if ($next->isPast()) {
            $next = match($this->frequency) {
                'daily'     => $next->addDay(),
                'weekly'    => $next->addWeek(),
                'biweekly'  => $next->addWeeks(2),
                'monthly'   => $next->addMonth(),
                'quarterly' => $next->addMonths(3),
                'yearly'    => $next->addYear(),
                default     => $next->addDay(),
            };
        }

        $this->next_run_at = $next;
        $this->save();
    }

    private function getNextWeeklyRun($time)
    {
        $dayOfWeek = $this->day_of_week ?? 'sun';
        $days = ['sun' => 0, 'mon' => 1, 'tue' => 2, 'wed' => 3, 'thu' => 4, 'fri' => 5, 'sat' => 6];
        $targetDay = $days[$dayOfWeek] ?? 0;

        return Carbon::now()->next($targetDay)->setTimeFrom($time);
    }

    private function getNextMonthlyRun($time)
    {
        $day = max(1, min(28, $this->day_of_month ?? 1));
        $next = Carbon::now()->day($day)->setTimeFrom($time);

        if ($next->isPast()) {
            $next->addMonth();
        }
        return $next;
    }
}
