<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOilComplete extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'services_oil_complete';

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
        'service_oil_id',
        'service_oil_odometer',
        'service_oil_date',
        'service_id',
        'odometer',
        'service_date',
        'life_span',
        'kms_recorridos',
        'elapsed_days',
        'display_name',
        'brand_name',
        'brand_id',
        'oil_viscosity',
        'type_oil',
        'qty',
        'filter_name',
        'filter_brand_id',
        'filter_brand_name'
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