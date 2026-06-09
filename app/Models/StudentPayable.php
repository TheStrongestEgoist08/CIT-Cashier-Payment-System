<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayable extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'payable_id',
        'payable_name',
        'payable_type',
        'grade_level',
        'school_year',
        'amount',
        'penalty_amount',
        'total_amount',
        'paid_amount',
        'due_date',
        'status',
        'remarks',
        'OR',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payable()
    {
        return $this->belongsTo(Payable::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isExempted(): bool
    {
        return $this->status === 'exempted';
    }

    public function getBalanceAttribute(): float
    {
        if ($this->status === 'exempted') {
            return 0;
        }

        return max(0, $this->total_amount - $this->paid_amount);
    }
}
