<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InspectionFluid extends Model
{
    use HasFactory;

    protected $sequence_id = 'inspection_fluid_sequence';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sequence_id',
        'service_id',
        'odoo_id',
        'transmission_case_oil',
        'transfer_oil',
        'gear_box_oil',
        'engine_coolant',
        'brake_fluid',
        'engine_oil',
        'brake_league',
        'cleaning_liquid',
        'fuel_tank',
        'steering_oil',
        'front_diff_oil',
        'rear_diff_oil'
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
}
