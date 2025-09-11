<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'date',
        'km_start',
        'km_end',
        'delivery_count',
        'price_per_delivery',
        'km_per_liter',
        'liters',
        'price_per_liter',
        'fuel_total',
        'gross',
        'net'
    ];

    protected $casts = [
        'date' => 'date',
        'km_start' => 'decimal:2',
        'km_end' => 'decimal:2',
        'delivery_count' => 'integer',
        'price_per_delivery' => 'decimal:2',
        'km_per_liter' => 'decimal:2',
        'liters' => 'decimal:2',
        'price_per_liter' => 'decimal:2',
        'fuel_total' => 'decimal:2',
        'gross' => 'decimal:2',
        'net' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get maintenances for the same date as this delivery
     */
    public function getMaintenancesAttribute()
    {
        return Maintenance::where('date', $this->date)->get();
    }
}
