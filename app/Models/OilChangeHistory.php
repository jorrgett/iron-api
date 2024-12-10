<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OilChangeHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'service_id',
        'service_state',
        'change_date',
        'change_km',
        'change_next_km',
        'change_next_date',
        'life_span',
        'life_span_standar',
        'change_next_days',
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
     * Get the services associated with the oil change history.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'odoo_id', 'service_id');
    }
}
