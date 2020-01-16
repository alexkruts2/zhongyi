<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    //
    protected $fillable = [
        'name',
        'phone',
        'department_id',
        'description',
        'from',
        'to',
        'visiting_place',
        'password'
    ];

}
