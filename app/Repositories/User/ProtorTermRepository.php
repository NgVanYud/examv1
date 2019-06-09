<?php


namespace App\Repositories\User;


use App\Models\ProtorTerm;
use App\Repositories\BaseRepository;

class ProtorTermRepository extends BaseRepository
{
  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return ProtorTerm::class;
  }
}
