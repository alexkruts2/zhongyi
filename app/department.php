<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    protected $fillable = [
        'name'
    ];
    public function hospital(){
        $this->belongsTo(hospital::class);
    }

}
