<?php


namespace App\Repositories\User;


use App\Models\Manager;
use App\Repositories\BaseRepository;

class ManagerRepository extends BaseRepository
{
  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Manager::class;
  }
}
