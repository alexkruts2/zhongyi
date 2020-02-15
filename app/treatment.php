<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class treatment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'request_time',
        'accept_by',
        'question_id',
        'question',
        'comment',
        'recipe',
        'guahao',
        'state',
        'treat_start',
        'treat_end',
        'record_video',
        'disease_name',
        'price',
        'doctor_profit',
        'hospital_profit',
        'original_recipe'
    ];

    public function patient(){
        return $this->belongsTo(patient::class,'patient_id');
    }
    public function doctor(){
        return $this->belongsTo(doctor::class,'doctor_id');
    }
    public function recipe(){
        return $this->belongsTo(recipe::class,'original_recipe');
    }
}
