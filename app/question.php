<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    protected $fillable = [
        'department_id',
        'number',
        'questions',
        'recipes',
        'title',
        'disease_name',
        'biaozheng',
        'lizheng',
        'biaoli',
        'maizheng',
        'fuDaiNumber',
        'medicines'
    ];
    public function department(){
        return $this->belongsTo(department::class);
    }

}
