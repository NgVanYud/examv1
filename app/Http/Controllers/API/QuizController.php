<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralException;
use App\Http\Resources\Quiz\QuizResource;
use App\Http\Resources\Result\ResultResource;
use App\Http\Resources\Term\SubjectTermResource;
use App\Repositories\Quiz\QuizRepository;
use App\Repositories\Result\ResultRepository;
use App\Repositories\User\StudentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
  protected $studentRepository;
  protected $quizRepository;
  protected $resultRepository;

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
    StudentRepository $studentRepository,
    QuizRepository $quizRepository,
    ResultRepository $resultRepository
  ){
    $this->studentRepository = $studentRepository;
    $this->quizRepository = $quizRepository;
    $this->resultRepository = $resultRepository;
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

  public function storeResult(Request $request, $subjectTerm) {
    $data = $request->only(['student', 'result', 'quiz']);
    return DB::transaction(function() use ($data, $subjectTerm) {
      $result = $data['result'];
      $quiz = $this->quizRepository->getById($data['quiz']);
      $student = $this->studentRepository->getByUuid($data['student']);
      $score = $this->getScore($data['result'], json_decode($quiz->answer));
      $result = $this->resultRepository->create([
        'student_code' => $student->code,
        'first_name' => $student->first_name,
        'last_name' => $student->last_name,
        'answer' => $score,
        'questions_total' => $quiz->question_num,
        'subject_term_id' => $subjectTerm->id,
        'quiz_id' => $quiz->id,
        'detail' => $result
      ]);
      $student->forceDelete();
      return new ResultResource($result);
    });

  }

  /**
   * Tinh diem bai thi
   *
   * @param array $answer
   * @param array $key
   * @return int
   */
  public function getScore($answer, $key) {
    $score = 0;
    $counter = count($key);
    for ($i = 0; $i < $counter; $i++) {
      if (isset($answer[$i]) && $key[$i] === $answer[$i]) {
        $score += 1;
      }
    }
    return $score;
  }
}
