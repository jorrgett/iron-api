<?php

namespace App\Models;

use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class VehicleSummary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'prom_km_month',
        'visits_number',
        'last_oil_change_date',
        'last_oil_change_km',
        'accum_km_traveled',
        'accum_days_total',
        'accum_oil_changes',
        'initial_date',
        'initial_km',
        'last_visit',
        'sequence_id'
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
     * Get vehicles that have summaries
     */
    public function vehicles(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'vehicle_id', 'vehicle_id');
    }

    /**
     * Get the services associated with the vehicle summaries.
     */
    public function services(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service::class,
            Vehicle::class,
            'odoo_id',
            'odoo_id',
            'vehicle_id',
            'odoo_id'
        );
    }
}
