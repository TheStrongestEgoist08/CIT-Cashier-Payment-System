<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payables', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->enum('type', [
                'tuition',
                'enrollment',
                'electricity',
                'assessment',
                'uniforms',
                'graduation',
                'others',
            ]);
            $table->json('details');
            $table->string('school_year', 9);
            $table->timestamps();
            $table->boolean('is_repeatable')
                ->default(0)
                ->after('school_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payables');
    }
};
