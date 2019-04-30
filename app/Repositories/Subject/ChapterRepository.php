<?php

namespace App\Repositories\Subject;

use App\Exceptions\GeneralException;
use App\Models\Chapter;
use App\Repositories\BaseRepository;

class ChapterRepository extends BaseRepository
{

  public $questionRepository;

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
  public function __construct(
    QuestionRepository $questionRepository
  )
  {
    $this->makeModel();
    $this->questionRepository = $questionRepository;
  }


  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Chapter::class;
  }


  /**
   * Get the specified model record from the database.
   *
   * @param       $id
   * @param array $columns
   *
   * @return Collection|Model
   */
  public function getById($id, array $columns = ['*'])
  {
    $this->unsetClauses();

    $this->newQuery()->eagerLoad();

    return $this->query->find($id, $columns);
  }


  public function existed($id) {
    if($this->getById($id)) {
      return true;
    }
    return false;
  }

  /**
   * Check if the chapter contains a question by Id
   *
   * @param $chapterId
   * @param $questionId
   * @return bool
   */
  public function containQuestion($chapterId, $questionId) {
    $question = $this->questionRepository->getById($questionId);
    if($question->chapter->id == $chapterId) {
      return true;
    }
    return false;
  }
}
