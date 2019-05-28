<?php


namespace App\Repositories\Subject;


use App\Models\Question;
use App\Repositories\BaseRepository;

class QuestionRepository extends BaseRepository
{
  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Question::class;
  }
}
