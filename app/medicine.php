<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class medicine extends Model
{
    protected $fillable = [
        'name',
        'usage',
        'weight',
        'price',
        'min_weight',
        'max_weight',
        'unit',
        'option',
        'flag',
    ];
}
