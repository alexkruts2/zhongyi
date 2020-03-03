<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class recipe extends Model
{
    protected $fillable = [
        'disease_name',
        'department_id',
        'condition',
        'other_condition',
        'medicine',
        'prescription_name',
        'eating_method',
        'ban',
        'flag'
    ];
    public function department(){
        return $this->belongsTo(department::class);
    }
    public function treatment(){
        $this->hasOne(treatment::class);
    }
}
