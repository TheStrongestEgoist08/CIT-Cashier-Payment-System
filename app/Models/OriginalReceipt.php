<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginalReceipt extends Model
{
    protected $fillable = [
        'id',
        'original_receipt_id',
        'created_at',
        'updated_at',
    ];
}
