<?php

namespace App\Models;

use App\Models\Service;
use App\Models\ServiceTire;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TireModel extends Model
{
    use HasFactory;

    protected $sequence_id = 'tire_models_sequence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odoo_id',
        'tire_brand_id',
        'name',
        'sequence_id'
    ];

    /**
    * Increment to 1 the sequence_id
    */
    public function incrementSequence(): int
    {
        return DB::scalar("SELECT nextval('$this->sequence_id')");
    }

    /**
     * Get all tire models through the services
     */
    public function services(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service::class,
            ServiceTire::class,
            'tire_model_id',
            'odoo_id',
            'odoo_id',
            'service_id'
        );
    }

    /**
     * Get all tire models through the services
     */
    public function battery_models(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service::class,
            ServiceBattery::class,
            'battery_model_id',
            'odoo_id',
            'odoo_id',
            'service_id'
        );
    }
}
