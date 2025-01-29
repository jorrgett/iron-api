<?php

namespace App\Models;

use App\Models\TireBrand;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceBattery extends Model
{
    use HasFactory;

    protected $table = "service_battery";
    protected $sequence_id = 'service_battery_sequence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sequence_id',
        'odoo_id',
        'battery_brand_id',
        'battery_model_id',
        'date_of_purchase',
        'serial_product',
        'warranty_date',
        'service_id',
        'amperage',
        'alternator_voltage',
        'battery_voltage',
        'status_battery',
        'status_alternator',
        'good_condition',
        'liquid_leakage',
        'corroded_terminals',
        'frayed_cables',
        'inflated',
        'cracked_case',
        'new_battery',
        'replaced_battery',
        'serial_product',
        'starting_current',
        'accumulated_load_capacity',
        'health_status',
        'health_percentage',
        'battery_charged',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    /**
    * Increment to 1 the sequence_id
    */
    public function incrementSequence(): int
    {
        return DB::scalar("SELECT nextval('$this->sequence_id')");
    }

    /**
     * Get the services associated with the aligments.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'odoo_id', 'service_id');
    }

    /**
     *
     *
     */
    public function tire_brands(): HasOne
    {
        return $this->hasOne(TireBrand::class, 'odoo_id', 'battery_brand_id');
    }

    /**
     *
     *
     */
    public function tire_models(): HasOne
    {
        return $this->hasOne(TireModel::class, 'odoo_id', 'battery_model_id');
    }
}
