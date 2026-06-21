<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'transaction_code',
        'total_amount',
        'total_penalty',
        'discount_amount',
        'payables',
        'remarks',
        'created_by'
    ];

    protected $casts = [
        'payables'      => 'array',
        'total_amount'  => 'decimal:2',
        'total_penalty' => 'decimal:2',
        'discount_amount'  => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
