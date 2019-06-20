<?php

namespace App;

use Illuminate\Notifications\Notifiable;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Eloquent implements AuthenticatableContract, MustVerifyEmailContract, CanResetPasswordContract
{
  use Authenticatable;	
  use Notifiable;
  use MustVerifyEmail;
  use CanResetPassword;

  protected $connection = 'mongodb';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password', 'role'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];
}
