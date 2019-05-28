<?php


namespace App\Repositories\Subject;


use App\Models\Option;
use App\Repositories\BaseRepository;

class OptionRepository extends BaseRepository
{
  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Option::class;
  }
}
