<?php


namespace App\Repositories\Subject;


use App\Models\Format;
use App\Repositories\BaseRepository;

class FormatRepository extends BaseRepository
{
  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Format::class;
  }

}
