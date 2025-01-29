<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceBatteryComplete extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'services_battery_complete';

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
        'vehicle_brand_model',
        'battery_brand_id',
        'battery_brand_name',
        'battery_model_id',
        'battery_model_name',
        'date_of_purchase',
        'warranty_date',
        'amperage',
        'battery_voltage',
        'alternator_voltage',
        'status_battery',
        'health_status',
        'health_percentage',
        'health_status_final',
        'good_condition',
        'liquid_leakage',
        'corroded_terminals',
        'frayed_cables',
        'inflated',
        'cracked_case',
        'new_battery',
        'battery_charged',
        'count_buen_estado',
        'count_fuga_de_liquido',
        'count_bornes_sulfatados',
        'count_cables_partidos',
        'count_carcasa_partida',
        'count_bateria_inflada',
        'count_recarga_bateria'
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