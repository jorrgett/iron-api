<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuiOemDepth extends Model
{
    use HasFactory;

    protected $table = "tui_oem_depths";
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tui_brand',
        'tui_model',
        'tui_size',
        'otd',
        'sequence_ie'
    ];

}
