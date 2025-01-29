<?php

namespace App\Models;

use App\Models\User;
use App\Models\Service;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Vehicle extends Model
{
    use HasFactory;

    protected $sequence_id = 'vehicles_sequence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odoo_id',
        'plate',
        'vehicle_brand_id',
        'brand_name',
        'vehicle_model_id',
        'model_name',
        'register_date',
        'color',
        'year',
        'transmission',
        'fuel',
        'odometer',
        'nickname',
        'color_hex',
        'icon',
        'type_vehicle',
        'odometer_unit',
        'sequence_id',
        'id',
        'uuid'
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
     * Get the services associated with the vehicle.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'vehicle_id', 'odoo_id');
    }

    public function vehicle_assignments(): HasMany
    {
        return $this->hasMany(VehicleAssignment::class, 'vehicle_id', 'id');
    }

    public function vehicle_brands(): HasOne
    {
        return $this->hasOne(VehicleBrand::class, 'odoo_id', 'vehicle_brand_id');
    }

    public function vehicle_models(): HasOne
    {
        return $this->hasOne(VehicleModel::class, 'odoo_id', 'vehicle_model_id');
    }

    /**
     * The users that belong to the vehicle
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'vehicle_assignments', 'vehicle_id', 'user_id');
    }

    public function serviceTimelines()
    {
        return $this->hasMany(ServiceTimeLine::class, 'vehicle_id', 'odoo_id');
    }

    /**
     * Scope a query to find vehicle by plate.
     */
    public function scopePlate(Builder $query, String $plate): void
    {
        $query->where('plate', $plate);
    }
}
