<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

class doctor extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'hospital_id',
        'name',
        'phone',
        'department_id',
        'introduction',
        'from',
        'to',
        'visiting_place',
        'password',
        'authority',
        'doctor_ratio'
    ];
    protected $guard = 'doctor';
    public function hospital(){
        $this->belongsTo(hospital::class);
    }

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
