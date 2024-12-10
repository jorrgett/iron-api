<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleAssignment extends Pivot
{
    use HasFactory;
    
    protected $table = 'vehicle_assignments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'service_associated',
    ];

    /**
     * Get the vehicle associated with the assignment.
     */
    public function vehicle()
    {
        return $this->belongsToMany(Vehicle::class, 'vehicle_assignments', 'user_id', 'vehicle_id');
    }

    /**
     * Get the user associated with the assignment.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'vehicle_assignments', 'vehicle_id', 'user_id');
    }
}
