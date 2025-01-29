<?php

namespace App\Models;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ServiceOperator extends Model
{
    use HasFactory;

    protected $sequence_id = 'service_operators_sequence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odoo_id',
        'vat',
        'name',
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
     * Get all service operators through the services
     */
    public function services(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service::class,
            ServiceItem::class,
            'operator_id',
            'odoo_id',
            'odoo_id',
            'service_id'
        );
    }
}
