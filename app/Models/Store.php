<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Store extends Model
{
    use HasFactory;

    protected $sequence_id = 'stores_sequence';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odoo_id',
        'name',
        'street',
        'street2',
        'city',
        'state',
        'country',
        'phone',
        'sequence_id',
        'latitude',
        'longitude',
        'photo_url',
        'photo_path',
        'is_active',
        'latitude_id',
        'length_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    /**
     * Increment to 1 the sequence_id
     */
    public function incrementSequence(): int
    {
        return DB::scalar("SELECT nextval('$this->sequence_id')");
    }

    /**
     * Get the services associated with the store.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'store_id', 'odoo_id');
    }

    /**
     * Get the available services through the store_services pivot table.
     */
    public function availableServices(): BelongsToMany
    {
        return $this->belongsToMany(ServiceProvided::class, 'store_services', 'store_id', 'service_id')
                    ->withPivot('available') 
                    ->wherePivot('available', true)
                    ->withTimestamps();
    }
}
