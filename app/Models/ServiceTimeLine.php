<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTimeLine extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'services_timeline';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'odoo_id',
        'store_id',
        'driver_id',
        'owner_id',
        'vehicle_id',
        'date',
        'odometer',
        'odometer_id',
        'state',
        'created_at',
        'updated_at',
        'sequence_id',
        'owner_name',
        'driver_name',
        'rotation_x',
        'rotation_lineal',
        'service_type',
        'next_service_date',
        'next_service_odometer'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'odoo_id');
    }
}
