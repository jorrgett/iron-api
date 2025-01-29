<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Vehicle;
use App\Models\Contacts;
use App\Models\Application;
use App\Models\ApplicationUser;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'full_name',
        'email',
        'password',
        'res_partner_id',
        'country_code',
        'phone',
        'email_verified_at',
        'avatar_url',
        'avatar_path',
        'language',
        'email_verified',
        'phone_verified',
        'legals_accepted',
        'terms_and_conditions_id',
        'legal_disclaimer_id',
        'privacy_policy_id',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'pivot'
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
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); 
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The users that belong to the role.
     */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class)->using(ApplicationUser::class);
    }

    /**
     * The vehicles that belong to the user.
     */
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'vehicle_assignments', 'user_id', 'vehicle_id');
    }
    

    public function contacts(): HasMany
    {
        return $this->hasMany(Contacts::class, 'phone', 'phone')
            ->orWhere('odoo_id', $this->res_partner_id);
    }    
}