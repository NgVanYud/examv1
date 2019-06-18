<?php

namespace App\Models;

use App\Models\Auth\User as Authenticable;
use App\Models\Traits\SendUserPasswordReset;

class Manager extends Authenticable
{

  use SendUserPasswordReset;

  protected $table = 'managers';

  protected $guard_name = 'manager';

  protected $fillable = [
    'last_name', 'first_name', 'username', 'uuid', 'email', 'password', 'is_actived', 'code', 'id'
  ];

  protected $hidden = [
    'password', 'remember_token',
  ];

  protected $dates = [
    'last_login_at', 'deleted_at', 'password_changed_at'
  ];
}
