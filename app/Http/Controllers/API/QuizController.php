<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralException;
use App\Http\Resources\Quiz\QuizResource;
use App\Http\Resources\Term\SubjectTermResource;
use App\Repositories\Quiz\QuizRepository;
use App\Repositories\User\StudentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuizController extends Controller
{
  protected $studentRepository;
  protected $quizRepository;

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
  public function __construct(StudentRepository $studentRepository, QuizRepository $quizRepository)
  {
    $this->studentRepository = $studentRepository;
    $this->quizRepository = $quizRepository;
  }

  public function index()
  {
    $user = auth('student')->user();
    $subjectTerm = $user->subjectTerm;
    return new SubjectTermResource($subjectTerm);
  }

  public function show(Request $request, $subjectTerm)
  {
    $user = auth('student')->user();
    $tmpUser = $this->studentRepository
      ->where('subject_term_id', $subjectTerm->id)
      ->where('uuid', $user->uuid)
      ->first();
    if (isset($tmpUser)) {
      $quiz = $this->quizRepository
        ->getById($tmpUser->quiz_id, [
          'id', 'code', 'detail', 'subject_term_id', 'is_actived', 'question_num', 'timeout'
        ]);
      return new QuizResource($quiz);
    }
    throw new GeneralException('Invalid data', 402);
  }
}
