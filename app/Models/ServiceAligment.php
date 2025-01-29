<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceAligment extends Model
{
    use HasFactory;

    protected $table = "service_alignment";
    protected $sequence_id = 'service_alignment_sequence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odoo_id',
        'service_id',
        'eje',
        'valor',
        'full_convergence_d',
        'semiconvergence_izq_d',
        'semiconvergence_der_d',
        'camber_izq_d',
        'camber_der_d',
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

    /**
     * Get the services associated with the aligments.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'odoo_id', 'service_id');
    }
}
