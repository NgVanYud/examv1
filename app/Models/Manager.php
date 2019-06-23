<?php

namespace App\Models;

use App\Models\Auth\User as Authenticable;
use App\Notifications\UserNeedsPasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class Manager extends Authenticable implements CanResetPassword
{

  use CanResetPasswordTrait;

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

  /**
   * Send the password reset notification.
   *
   * @param string $token
   */
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new UserNeedsPasswordReset($token, $this));
  }
}
