<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceProvided extends Model
{
    use HasFactory;
    protected $table = 'services_provided';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'short_name',
        'description',
        'icon'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'deleted_at' => 'datetime:Y-m-d H:m:s'
    ];

    /**
     * Get the stores that provide this service.
     */
    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_services', 'service_id', 'store_id')
                    ->wherePivot('available', true)
                    ->withTimestamps();
    }
}
