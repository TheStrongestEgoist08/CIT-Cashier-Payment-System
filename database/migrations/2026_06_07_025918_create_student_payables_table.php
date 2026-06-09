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
        Schema::create('student_payables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('payable_id')->constrained('payables')->onDelete('cascade');
            $table->string('payable_name');
            $table->string('payable_type');
            $table->enum('grade_level', ['Grade 11', 'Grade 12']);
            $table->string('school_year', 9);
            $table->decimal('amount', 10, 2);
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->enum('status', ['paid', 'unpaid','exempted'])->default('unpaid');
            $table->string('remarks')->nullable();

            // Purchase Token for repeatable items
            $table->string('purchase_token')->nullable();

            $table->timestamps();

            // Unique constraint that allows multiple purchases
            $table->unique(
                ['student_id', 'payable_id', 'school_year', 'purchase_token'],
                'uq_student_payables'
            );

            $table->index(['school_year', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payables');
    }
};
