<?php


namespace App\Repositories\Quiz;


use App\Models\Quiz;
use App\Models\StudentTerm;
use App\Repositories\BaseRepository;
use App\Repositories\Subject\ChapterRepository;
use App\Repositories\Subject\FormatRepository;
use App\Repositories\Subject\QuestionRepository;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Term\TermRepository;
use App\Repositories\User\StudentTermRepository;
use Illuminate\Support\Facades\DB;

class QuizRepository extends BaseRepository
{
  public $formatRepository;
  public $subjectRepository;
  public $termRepository;
  public $questionRepository;
  public $chapterRepository;
  public $studentTermRepository;

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
  public function __construct(FormatRepository $formatRepository,
                              SubjectRepository $subjectRepository,
                              TermRepository $termRepository,
                              QuestionRepository $questionRepository,
                              ChapterRepository $chapterRepository,
                              StudentTermRepository $studentTermRepository
                              ) {
    $this->formatRepository = $formatRepository;
    $this->subjectRepository = $subjectRepository;
    $this->termRepository = $termRepository;
    $this->questionRepository = $questionRepository;
    $this->chapterRepository = $chapterRepository;
    $this->studentTermRepository = $studentTermRepository;
    $this->makeModel();
  }


  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Quiz::class;
  }

  /**
   * @param $subjectTermId
   * @param $termId
   * @param $subjectId
   * @param $quizNum
   * @return mixed (all quizs created)
   */
  public function createQuiz($subjectTermId, $termId, $subjectId, $quizNum) {
    return DB::transaction(function () use ($termId, $subjectId, $quizNum, $subjectTermId) {
      $formatQuiz = ($this->formatRepository->getByColumn($subjectId, 'subject_id'));
      $term = $this->termRepository->getById($termId);
      $subject = $this->subjectRepository->getById($subjectId);
      $format = $formatQuiz->format;
      $allQuizs = [];
      for ($i = 0; $i < $quizNum; $i++) {
        $quizCode = 'DT'.$i.'-'.$term->code.'-'.$subject->code;
        $newQuiz = $this->create(['code' => $quizCode, 'subject_term_id' => $subjectTermId]);
        $order = 1;
        foreach ($format as $chapterId => $quesNum) {
          if($quesNum > 0) {
            $tmpQuestions = $this->chapterRepository->getRandomQuestions($chapterId, $quesNum);
            if(count($tmpQuestions) > 0) {
              foreach ($tmpQuestions as $tmpQuestion) {
                $newQuiz->questions()->attach($tmpQuestion->id, ['order' => $order]);
                $order += 1;
              }
            }
          }
        }
        $allQuizs[] = $newQuiz;
      }
      return $allQuizs;
    });
  }

  /**
   * Assign the random quiz to student
   *
   * @param $quizs
   * @param $students
   */
  public function assignQuizs($quizs, $students) {
    $quizNum = count($quizs);
    foreach ($students as $student) {
      $randomIndex = rand(0, $quizNum - 1);
      $this->studentTermRepository->create(['student_id' => $student->id, 'quiz_id' => ($quizs[$randomIndex])->id, 'subject_term_id' => ($quizs[$randomIndex])->subject_term_id]);
    }
  }
}
