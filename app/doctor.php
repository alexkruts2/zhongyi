<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

class doctor extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'hospital_name',
        'name',
        'phone',
        'department_id',
        'introduction',
        'from',
        'to',
        'visiting_place',
        'password',
        'authority'
    ];
    protected $guard = 'doctor';

    public function department(){
        return $this->belongsTo(department::class);
    }
    public function treatment(){
        $this->hasOne(treatment::class);
    }
    public function question(){
        $this->hasOne(question::class);
    }

}
