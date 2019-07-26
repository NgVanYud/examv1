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
    $curatorRole = $this->roleRepository->getByColumn(config('access.roles_list.curator'), 'name', ['id']);
    return $user->hasAnyRole([$adminRole->id, $examsMakerRole->id, $curatorRole->id]);
  }

  public function create($user) {
    return $user->hasRole(config('access.roles_list.admin'));
  }

  public function update($user, $model) {
    return $user->hasRole(config('access.roles_list.admin'));
  }

  public function view($user, $model) {
    return true;
  }

  public function delete($user, $model) {
    return $user->hasRole(config('access.roles_list.admin'));
  }
}
