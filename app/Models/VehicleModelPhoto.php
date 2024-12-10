<?php

namespace App\Models;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class VehicleModelPhoto extends Model
{
    use HasFactory, FilterQueryString;

    protected $filters = [
        'id', 
        'brand_id', 
        'model_id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand_id',
        'model_id',
        'year',
        'color',
        'photo_url',
        'photo_path',
        'is_active'
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

    public function brands(): HasOne
    {
        return $this->hasOne(VehicleBrand::class, 'odoo_id', 'brand_id');
    }

    public function models(): HasOne
    {
        return $this->hasOne(VehicleModel::class, 'odoo_id', 'model_id');
    }
    
}
