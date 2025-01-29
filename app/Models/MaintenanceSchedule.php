<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $fillable = [
       'vehicle_id',
       'order',
       'maintenance_kms',
       'maintenance_interval',
       'status',
       'done_date'
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

}