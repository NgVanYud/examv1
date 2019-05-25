<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectPolicy
{
    use HandlesAuthorization;

  public function before($user, $ability)
  {
    $rolesAdmin = $this->roleRepository->getByColumn(config('access.roles_list.admin'), 'name', ['id']);
    return $user->hasRole($rolesAdmin->id);
  }

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
