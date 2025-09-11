<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'date',
        'description',
        'cost',
        'km'
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
        'km' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
