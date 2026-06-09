<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    protected $fillable = [
        'name',
        'type',
        'details',
        'school_year',
        'is_repeatable',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function studentPayables()
    {
        return $this->hasMany(StudentPayable::class);
    }
}
