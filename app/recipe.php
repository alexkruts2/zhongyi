<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class recipe extends Model
{
    protected $fillable = [
        'disease_name',
        'recipe_part_id',
        'condition',
        'other_condition',
        'medicine',
        'prescription_name'
    ];
    public function recipe_part(){
        return $this->belongsTo(recipe_part::class);
    }
    public function treatment(){
        $this->hasOne(treatment::class);
    }
}
