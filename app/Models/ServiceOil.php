<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceOil extends Model
{
    use HasFactory;

    protected $table = "service_oil";
    protected $sequence_id = 'service_oil_sequence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odoo_id',
        'service_id',
        'tire_brand_id',
        'oil_viscosity',
        'type_oil',
        'life_span',
        'oil_quantity',
        'filter_brand_id'
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
}
