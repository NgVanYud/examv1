<?php

namespace App\Repositories\Subject;

use App\Models\Chapter;
use App\Repositories\BaseRepository;

class ChapterRepository extends BaseRepository
{

  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Chapter::class;
  }
}
