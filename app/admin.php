<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class admin extends Authenticatable
{
    protected $fillable = [
      'name',
      'password'
    ];
    protected $guard = 'admin';
}
