<?php

namespace App\Policies;

use App\Models\User;
use App\Repositories\Role\RoleRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectPolicy
{
    use HandlesAuthorization;

  public $roleRepository;

  /**
   * Create a new policy instance.
   *
   * @return void
   */
  public function __construct(RoleRepository $roleRepository)
  {
    $this->roleRepository = $roleRepository;
  }

  public function before($user, $ability)
  {
    $adminRole = $this->roleRepository->getByColumn(config('access.roles_list.admin'), 'name', ['id']);
    $examsMakerRole = $this->roleRepository->getByColumn(config('access.roles_list.exams_maker'), 'name', ['id']);

    return $user->hasAnyRole([$adminRole->id, $examsMakerRole->id]);
  }


}
