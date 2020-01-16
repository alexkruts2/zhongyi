<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class patient extends Model
{
    //
    protected $fillable = [
        'phone_number',
        'ID_Number',
        'name',
        'img_url',
        'sex'
    ];
    public function treatment(){
        $this->hasOne(treatment::class);
    }

}
