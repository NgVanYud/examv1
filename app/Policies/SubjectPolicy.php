<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Manager;
use App\Models\Subject;
use App\Repositories\Role\RoleRepository;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

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

  public function before(User $user, $ability)
  {
    $adminRole = $this->roleRepository->getByColumn(config('access.roles_list.admin'), 'name', ['id']);
    $examsMakerRole = $this->roleRepository->getByColumn(config('access.roles_list.exams_maker'), 'name', ['id']);
    $curatorRole = $this->roleRepository->getByColumn(config('access.roles_list.curator'), 'name', ['id']);
    return $user->hasAnyRole([$adminRole->id, $examsMakerRole->id, $curatorRole->id]);
  }

  public function create(User $user) {
    return $user->hasRole(config('access.roles_list.admin'));
  }

  public function update(User $user, Subject $model) {
    return $user->hasRole(config('access.roles_list.admin'));
  }

  public function view(User $user, Subject $model) {
    return true;
  }

  public function delete(User $user, Subject $model) {
    return $user->hasRole(config('access.roles_list.admin'));
  }
}
