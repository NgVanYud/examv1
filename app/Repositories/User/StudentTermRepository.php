<?php


namespace App\Repositories\User;


use App\Models\StudentTerm;
use App\Repositories\BaseRepository;

class StudentTermRepository extends BaseRepository
{
  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return StudentTerm::class;
  }
}
