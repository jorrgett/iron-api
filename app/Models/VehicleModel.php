<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class VehicleModel extends Model
{
    use HasFactory;

    protected $sequence_id = 'vehicle_models_sequence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odoo_id',
        'vehicle_brand_id',
        'name',
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

    public function models()
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_brand_id', 'odoo_id');
    }
    
    /**
     * Get all vehicle models through the services
     */
    public function services(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service::class,
            Vehicle::class,
            'vehicle_model_id',
            'vehicle_id',
            'odoo_id',
            'odoo_id'
        );
    }
}
