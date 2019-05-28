<?php

namespace App\Policies;

use App\Models\User;
use App\Repositories\Role\RoleRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public $roleRepository;

  /**
   * PHP 5 allows developers to declare constructor methods for classes.
   * Classes which have a constructor method call this method on each newly-created object,
   * so it is suitable for any initialization that the object may need before it is used.
   *
   * Note: Parent constructors are not called implicitly if the child class defines a constructor.
   * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
   *
   * param [ mixed $args [, $... ]]
   * @link https://php.net/manual/en/language.oop5.decon.php
   */
  public function __construct(RoleRepository $roleRepository)
  {
    $this->roleRepository = $roleRepository;
  }

  public function before($user, $ability)
  {
    $rolesAdmin = $this->roleRepository->getByColumn(config('access.roles_list.admin'), 'name', ['id']);
    return $user->hasRole($rolesAdmin->id);
  }

  public function viewAll(User $user) {
    return true;
  }

  /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
      return !$model->hasRole(config('access.roles_list.admin'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
