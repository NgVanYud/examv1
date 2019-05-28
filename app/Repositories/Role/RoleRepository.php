<?php


namespace App\Repositories\Role;


use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository
{
  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Role::class;
  }

  /**
   * Get roles excepts some
   *
   * $roles (array of ids)
   */
  public function getExcept($roles, $columns = ['*']) {
    return $this->get($columns)->except($roles);
  }

}
