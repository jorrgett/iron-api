<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTireSummary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'tire_location',
        'prom_tire_km_month',
        'prom_tire_mm_x_visit',
        'months_to_tire_unsafe',
        'projected_tire_visits',
        'estimated_months_tire_visits',
        'accum_km_traveled',
        'accum_days_total',
        'life_span_consumed'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'prom_tire_km_month'           => 'double',
        'prom_tire_mm_x_visit'         => 'double',
        'months_to_tire_unsafe'        => 'double',
        'projected_tire_visits'        => 'integer',
        'estimated_months_tire_visits' => 'double',
        'accum_days_total'             => 'integer',
        'life_span_consumed'           => 'double',
        'accum_km_traveled'            => 'integer',
        'created_at'                   => 'datetime:Y-m-d H:m:s',
        'updated_at'                   => 'datetime:Y-m-d H:m:s'
    ];
}
