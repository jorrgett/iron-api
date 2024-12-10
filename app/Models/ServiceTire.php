<?php

namespace App\Models;

use App\Models\Service;
use App\Models\TireSize;
use App\Models\TireModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceTire extends Model
{
    use HasFactory;

    protected $sequence_id = 'service_tires_sequence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odoo_id',
        'service_id',
        'location',
        'depth',
        'starting_pressure',
        'finishing_pressure',
        'dot',
        'tire_brand_id',
        'tire_model_id',
        'tire_size_id',
        'sequence_id',
        'tire_change',
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
        'depth_original'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'depth'      => 'float'
    ];

    /**
    * Increment to 1 the sequence_id
    */
    public function incrementSequence(): int
    {
        return DB::scalar("SELECT nextval('$this->sequence_id')");
    }

    /**
     * Get the services associated with the service tires.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'odoo_id', 'service_id');
    }

    public function tire_brands(): HasOne
    {
        return $this->hasOne(TireBrand::class, 'odoo_id', 'tire_brand_id');
    }

    public function tire_models(): HasOne
    {
        return $this->hasOne(TireModel::class, 'odoo_id', 'tire_model_id');
    }

    public function tire_sizes(): HasOne
    {
        return $this->hasOne(TireSize::class, 'odoo_id', 'tire_size_id');
    }
}
