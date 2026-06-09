<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BackupSchedule;
use Illuminate\Support\Facades\Artisan;

class RunScheduledBackup extends Command
{
    protected $signature = 'backup:run-scheduled';
    protected $description = 'Execute scheduled database backups';

    public function handle()
    {
        $schedule = BackupSchedule::getSettings();

        if (!$schedule->is_enabled) {
            $this->info('Automatic backup is disabled.');
            return;
        }

        // If no next run time set or it's in the future → skip
        if (!$schedule->next_run_at || $schedule->next_run_at->isFuture()) {
            $this->info('No backup due at this time. Next run: ' .
                       ($schedule->next_run_at?->format('Y-m-d H:i:s') ?? 'Not set'));
            return;
        }

        $this->info('🔄 Starting scheduled backup...');
        $this->info('Due time was: ' . $schedule->next_run_at->format('Y-m-d H:i:s'));

        try {
            $result = Artisan::call('backup:manual', [
                '--path_id' => $schedule->backup_path_id
            ]);

            if ($result === 0) {
                $schedule->update(['last_run_at' => now()]);
                $schedule->updateNextRun();

                $this->info('✅ Scheduled backup completed successfully!');
                $this->info('Next backup scheduled at: ' . $schedule->fresh()->next_run_at?->format('Y-m-d H:i:s'));
            } else {
                $this->error('❌ Scheduled backup failed with error code: ' . $result);
            }
        } catch (\Exception $e) {
            $this->error('💥 Error during backup: ' . $e->getMessage());
            \Log::error('Scheduled Backup Failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
