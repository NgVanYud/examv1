<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralException;
use App\Exports\UsersExport;
use App\Http\Requests\StoreSettingSubjectTermRequest;
use App\Http\Resources\Quiz\QuizResource;
use App\Http\Resources\Result\ResultResource;
use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\Term\SubjectTermResource;
use App\Http\Resources\User\ManagerResource;
use App\Http\Resources\User\StudentResource;
use App\Models\SubjectTerm;
use App\Repositories\Result\ResultRepository;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Term\SubjectTermRepository;
use App\Repositories\Term\TermRepository;
use App\Repositories\User\StudentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class SubjectTermController extends Controller
{
    public $subjectTermRepository;
    public $termRepository;
    public $protorTermRepository;
    public $subjectRepository;
    public $studentRespository;
    public $resultRepository;

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
    SubjectTermRepository $subjectTermRepository,
    TermRepository $termRepository,
    SubjectRepository $subjectRepository,
    StudentRepository $studentRepository,
    ResultRepository $resultRepository
  ){
    $this->subjectTermRepository = $subjectTermRepository;
    $this->termRepository = $termRepository;
    $this->subjectRepository = $subjectRepository;
    $this->studentRespository = $studentRepository;
    $this->resultRepository = $resultRepository;
  }

  public function index() {
  }

  public function show(Request $request, $term, $subject) {
    $termDetail = $this->subjectTermRepository
      ->where('term_id', $term->id)
      ->where('subject_id', $subject->id)
      ->first();
    return new SubjectTermResource($termDetail);
  }

//  public function store(StoreSettingSubjectTermRequest $request, $term, $subject) {
  public function store(Request $request, $term, $subject) {
    return DB::transaction(function() use ($request, $term, $subject) {
      $studentData = $this->subjectTermRepository->storeSetting($term->id, $subject->id, $request->all());
      return $studentData;
    });
  }

  public function exportUserData($data, $filename = 'ds_user') {
    return Excel::download(new UsersExport($data), $filename.'.xlsx');
  }

  public function subjectsForTerm(Request $request) {
    $currentUser = auth('manager')->user();
    $subjectTerms = $currentUser->terms()
      ->actived()
      ->configed()
      ->get();
    return SubjectTermResource::collection($subjectTerms);
  }

  public function getStudents(Request $request, $term, $subject) {
    $subjectTerm = $this->subjectTermRepository->where('term_id', $term->id)
      ->where('subject_id', $subject->id)
      ->first();
    $conditions = [
      'orderBy' => ($request->orderBy ? $request->orderBy : 'first_name'),
      'order' => ($request->order && in_array($request->order, ['desc', 'asc']) ? $request->order : 'asc'),
      'limit' => ($request->limit && intval($request->limit) > 0 ? $request->limit : 10),
      'search' => ($request->search ? $request->search : ''),
      'roles' => ($request->roles ? $request->roles : ''),
    ];
    $students = $this->studentRespository
      ->where('subject_term_id', $subjectTerm->id)
      ->orderBy($conditions['orderBy'], $conditions['order'])
      ->paginate($conditions['limit']);
    return StudentResource::collection($students);
  }

  public function getProtors($term, $subject) {
    $subjectTerm = $this->subjectTermRepository->where('term_id', $term->id)
      ->where('subject_id', $subject->id)
      ->first();
    $protors = ManagerResource::collection($subjectTerm->protors);
    return $protors;
  }

  public function getQuizs($term, $subject) {
    $subjectTerm = $this->subjectTermRepository->where('term_id', $term->id)
      ->where('subject_id', $subject->id)
      ->first();
    $quizs = QuizResource::collection($subjectTerm->quizs);
    return $quizs;
  }

  public function activeQuiz(Request $request) {
    $subjectTermId = $request->subject_term_id;
    $subjectTerm = $this->subjectTermRepository->getById($subjectTermId);
    if (Gate::allows('active-quiz', $subjectTerm)) {
      return $this->subjectTermRepository->activeQuiz($subjectTermId);
    }
    throw new GeneralException('Invalid data', 401);
  }

  public function deactiveQuiz(Request $request) {
    $subjectTermId = $request->subject_term_id;
    $subjectTerm = $this->subjectTermRepository->getById($subjectTermId);
    if (Gate::allows('deactive-quiz', $subjectTerm)) {
      return $this->subjectTermRepository->deactiveQuiz($subjectTermId);
    }
    throw new GeneralException('Invalid data', 401);
  }

  public function getResults(Request $request, $subjectTerm) {
    $conditions = [
      'orderBy' => ($request->orderBy ? $request->orderBy : 'first_name'),
      'order' => ($request->order && in_array($request->order, ['desc', 'asc']) ? $request->order : 'asc'),
      'limit' => ($request->limit && intval($request->limit) > 0 ? $request->limit : 10)
    ];
    $results = $this->resultRepository
      ->where('subject_term_id', $subjectTerm->id)
      ->orderBy($conditions['orderBy'], $conditions['order'])
      ->paginate($conditions['limit']);

    return ResultResource::collection($results);
  }

  public function active(Request $request) {
    $subjectTermId = $request->subject_term_id;
    return new SubjectTermResource($this->subjectTermRepository->active($subjectTermId));
  }

  public function deactive(Request $request) {
    $subjectTermId = $request->subject_term_id;
    return new SubjectTermResource($this->subjectTermRepository->deactive($subjectTermId));
  }
}
