<?php

namespace App\Models;

use App\Models\ServiceItem;
use App\Models\ServiceBattery;
use App\Models\VehicleSummary;
use App\Models\ServiceAligment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $sequence_id = 'services_sequence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odoo_id',
        'store_id',
        'driver_id',
        'owner_id',
        'vehicle_id',
        'date',
        'odometer',
        'odometer_id',
        'state',
        'owner_name',
        'driver_name',
        'sequence_id',
        'rotation_x',
        'rotation_lineal'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rotation_x'      => 'boolean',
        'rotation_lineal' => 'boolean',
        'created_at'       => 'datetime:Y-m-d H:m:s',
        'updated_at'       => 'datetime:Y-m-d H:m:s'
    ];

    /**
    * Increment to 1 the sequence_id
    */
    public function incrementSequence(): int
    {
        return DB::scalar("SELECT nextval('$this->sequence_id')");
    }

    /**
     * Get stores that have services.
     */
    public function stores(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'odoo_id');
    }

    /**
     * Get drivers that have services.
     */
    public function drivers(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'res_partner_id');
    }

    /**
     * Get vehicles that have services.
     */
    public function vehicles(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'odoo_id');
    }

    /**
     * Get all of the services for the items.
     */
    public function serviceItems(): HasMany
    {
        return $this->hasMany(ServiceItem::class, 'service_id', 'odoo_id');
    }

    /**
     * Get the summaries associated with the vehicle.
     */
    public function summaries(): HasMany
    {
        return $this->hasMany(VehicleSummary::class, 'vehicle_id', 'vehicle_id');
    }

    /**
     * Get aligments that have services.
     */
    public function aligment(): HasMany
    {
        return $this->hasMany(ServiceAligment::class, 'service_id', 'odoo_id');
    }

    /**
     * Get all of the services for the batteries
     */
    public function serviceBattery(): HasMany
    {
        return $this->hasMany(ServiceBattery::class, 'service_id', 'odoo_id');
    }

    public function serviceBalancing(): HasMany
    {
        return $this->hasMany(ServiceBalancing::class, 'service_id', 'odoo_id')
                    ->where('balanced', true);
    }

    public function serviceTires(): HasMany
    {
        return $this->hasMany(ServiceTire::class, 'service_id', 'odoo_id');
    }

    public function serviceOil(): HasMany
    {
        return $this->hasMany(ServiceOil::class, 'service_id', 'odoo_id');
    }

    public function serviceFluids(): HasMany
    {
        return $this->hasMany(InspectionFluid::class, 'service_id', 'odoo_id');
    }
}
