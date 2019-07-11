<?php


namespace App\Models\Auth;

use App\Models\Traits\UserAttributes;
use App\Models\Traits\UserMethods;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Role;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Traits\HasRoles;


class User extends Authenticatable implements JWTSubject
{

  use UserAttributes, Uuid, SoftDeletes, Notifiable, HasRoles, UserMethods;

  const ACTIVED_CODE = 1;
  const DEACTIVED_CODE = 0;

  protected $appends = ['full_name'];

  protected $casts = [
    'is_actived' => 'boolean',
  ];

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
}
