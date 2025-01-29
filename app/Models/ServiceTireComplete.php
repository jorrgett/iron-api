<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTireComplete extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'services_tires_complete';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'res_partner_id',
        'full_name',
        'email',
        'phone',
        'ubicacion',
        'vehicle_id',
        'plate',
        'vehicle_brand_id',
        'vehicle_brand_name',
        'vehicle_model_id',
        'vehicle_model_name',
        'service_id',
        'odometer',
        'service_date',
        'tire_brand_id',
        'tire_brand_name',
        'tire_model_id',
        'tire_model_name',
        'tire_size_id',
        'tire_size_name',
        'tire_location',
        'tread_depth',
        'lifespan_consumed',
        'mm_consumed',
        'km_traveled',
        'km_proyected',
        'otd',
        'performance_index',
        'prom_performance_index',
        'dot',
        'starting_pressure',
        'finishing_pressure',
        'regular',
        'staggered',
        'central',
        'right_shoulder',
        'left_shoulder',
        'not_apply',
        'bulge',
        'perforations',
        'vulcanized',
        'aging',
        'cracked',
        'deformations',
        'separations',
        'tire_change',
        'count_not_apply',
        'count_bulge',
        'count_perforations',
        'count_vulcanized',
        'count_aging',
        'count_cracked',
        'count_deformations',
        'count_separations',
        'count_25',
        'count_50',
        'count_75',
        'count_100'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'res_partner_id', 'res_partner_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'odoo_id');
    }
}