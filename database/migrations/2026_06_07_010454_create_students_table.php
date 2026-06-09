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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50)->unique();
            $table->string('LRN', 50)->unique();
            $table->string('complete_name', 100);
            $table->enum('sex', ['Male', 'Female']);
            $table->enum('grade_level', ['Grade 11', 'Grade 12']);
            $table->string('school_year', 9);
            $table->string('section', 100);
            $table->string('cluster', 100);
            $table->enum('classification', ['Regular Payee', 'ESC Grantee', 'Voucher Beneficiary'])->default('Regular Payee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
