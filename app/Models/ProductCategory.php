<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class ProductCategory extends Model
{
    use FilterQueryString;

    protected $sequence_id = 'product_categories_sequence';

    protected $filters = [
        'registered',
        'is_null',
        'category_id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'sequence_id'
    ];

    /**
    * Increment to 1 the sequence_id
    */
    public function incrementSequence(): int
    {
        return DB::scalar("SELECT nextval('$this->sequence_id')");
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    public function is_null($query)
    {
        return $query->whereNull('category_id');
    }

    public function registered($query)
    {
        return $query->whereNotNull('category_id');
    }
}
