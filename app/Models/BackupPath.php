<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupPath extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'path', 'is_default', 'is_active'];

    public function schedules()
    {
        return $this->hasMany(BackupSchedule::class);
    }
}
