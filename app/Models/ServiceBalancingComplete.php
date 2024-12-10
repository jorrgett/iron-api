<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceBalancingComplete extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'services_balancing_complete';

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
        'service_balancing_id',
        'service_balancing_odometer',
        'service_balancing_date',
        'service_id',
        'odometer',
        'service_date',
        'kms_recorridos',
        'elapsed_days'
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