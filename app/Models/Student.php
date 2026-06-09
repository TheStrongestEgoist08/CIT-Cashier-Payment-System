<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_id',
        'LRN',
        'complete_name',
        'sex',
        'grade_level',
        'school_year',
        'section',
        'cluster',
        'classification',
    ];

    protected $casts = [
        'sex' => 'string',
        'grade_level' => 'string',
        'classification' => 'string',
    ];

    public function studentPayables()
    {
        return $this->hasMany(StudentPayable::class);
    }
}
