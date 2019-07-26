<?php

namespace App\Models;

use App\Models\Auth\User as Authenticable;
use App\Models\Traits\Filterable;
use App\Notifications\UserNeedsPasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class Manager extends Authenticable implements CanResetPassword
{

  use CanResetPasswordTrait, Filterable;

  protected $table = 'managers';

  protected $guard_name = 'manager';

  protected $filterable = [
    'last_name', 'first_name', 'username', 'email'
  ];

  protected $fillable = [
    'last_name', 'first_name', 'username', 'uuid', 'email', 'password', 'is_actived', 'code', 'id'
  ];

  protected $hidden = [
    'password', 'remember_token',
  ];

  protected $dates = [
    'last_login_at', 'deleted_at', 'password_changed_at'
  ];

  public function filterUsername($query, $value) {
    return $query->where('username', 'LIKE', '%'.$value.'%');
  }

  public function createPwdResetToken() {
    $token = app('auth.password.broker')->createToken($this);
    return $token;
  }

  /**
   * Send the password reset notification.
   *
   * @param string $token
   */
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new UserNeedsPasswordReset($token, $this));
  }

  public function quizMakeSubjects() {
    return $this->belongsToMany('App\Models\Subject', 'quizs_maker_subject', 'quizs_maker_id','subject_id');
  }
}
