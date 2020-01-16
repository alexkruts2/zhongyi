<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class recipe_part extends Model
{
    protected $fillable = [
        'name'
    ];

    public function recipes(){
        return $this->hasMany(recipe::class);
    }
}
