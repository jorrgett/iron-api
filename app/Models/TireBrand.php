<?php

namespace App\Models;

use App\Models\ServiceTire;
use App\Models\ServiceBattery;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TireBrand extends Model
{
    use HasFactory;

    protected $sequence_id = 'tire_brands_sequence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odoo_id',
        'name',
        'url_image',
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
     * Increment to 1 the sequence_id
     */
    public function incrementSequence(): int
    {
        return DB::scalar("SELECT nextval('$this->sequence_id')");
    }

    /**
     * Get all tire brands through the services
     */
    public function services(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service::class,
            ServiceTire::class,
            'tire_brand_id',
            'odoo_id',
            'odoo_id',
            'service_id'
        );
    }

    /**
     * Get all battery brands through the services
     */
    public function service_batteries(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service:: class,
            ServiceBattery::class,
            'battery_brand_id',
            'odoo_id',
            'odoo_id',
            'service_id'
        );
    }

    /**
     * Get all battery brands through the services
     */
    public function service_oil(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service:: class,
            ServiceOil::class,
            'tire_brand_id',
            'odoo_id',
            'odoo_id',
            'service_id'
        );
    }
}
