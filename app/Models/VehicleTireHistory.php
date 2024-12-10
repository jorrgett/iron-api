<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTireHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'service_id',
        'service_date',
        'odometer',
        'tire_location',
        'otd',
        'tread_depth',
        'mm_consumed',
        'performance_index',
        'km_traveled',
        'km_proyected',
        'odometer_estimated',
        'safe_depth',
        'lifespan_consumed',
        'months_between_visits',
        'sequence_id'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'sequence_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'otd'                   => 'decimal:2',
        'tread_depth'           => 'decimal:2',
        'mm_consumed'           => 'integer',
        'odometer'              => 'integer',
        'safe_depth'            => 'float',
        'lifespan_consumed'     => 'float',
        'months_between_visits' => 'integer'
    ];
}
