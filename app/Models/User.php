<?php

namespace App\Models;

use App\Models\Traits\SendUserPasswordReset;
use App\Models\Traits\Uuid;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\UserAttributes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, CanResetPassword
{
    use Notifiable, HasRoles, UserAttributes, Uuid, SoftDeletes, SendUserPasswordReset;

    const ACTIVE_CODE = 1;
    const DEACTIVE_CODE = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'last_name', 'first_name', 'username', 'uuid', 'email', 'password', 'active', 'code', 'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['last_login_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     * @var array
     */
    protected $appends = ['full_name', 'action_buttons'];

    public function isActive() {
        return $this->active;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

  public function getRoleIds(): Collection
  {
    return $this->roles->pluck('id');
  }

  public function getPermissionIds(): Collection
  {
    return $this->permissions->pluck('id');
  }

  /**
   * Get the e-mail address where password reset links are sent.
   *
   * @return string
   */
  public function getEmailForPasswordReset()
  {
    return $this->email;
  }

  public function createPwdResetToken() {
    $token = app('auth.password.broker')->createToken($this);
    return $token;
  }

  public function subjects() {
    return $this->belongsToMany('App\Models\Subject', 'exams_maker_subject', 'exam_maker_id', 'subject_id');
  }

  public function term() {
    return $this->hasOne(StudentTerm::class, 'student_id');
  }

}
