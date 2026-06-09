<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backup_schedules', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_enabled')->default(false);

            $table->enum('frequency', [
                'daily',
                'weekly',
                'biweekly',
                'monthly',
                'quarterly',
                'yearly'
            ])->default('daily');

            $table->time('backup_time')->default('02:00:00');

            // For weekly & biweekly
            $table->string('day_of_week')->nullable();

            // For monthly, quarterly, yearly
            $table->tinyInteger('day_of_month')->nullable()->unsigned();

            $table->foreignId('backup_path_id')->constrained('backup_paths')->onDelete('cascade');

            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_schedules');
    }
};
