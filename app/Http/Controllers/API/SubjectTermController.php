<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralException;
use App\Exports\UsersExport;
use App\Http\Requests\StoreSettingSubjectTermRequest;
use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\Term\SubjectTermResource;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Term\SubjectTermRepository;
use App\Repositories\Term\TermRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SubjectTermController extends Controller
{
    public $subjectTermRepository;
    public $termRepository;
    public $protorTermRepository;
    public $subjectRepository;

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
  public function __construct(SubjectTermRepository $subjectTermRepository, TermRepository $termRepository, SubjectRepository $subjectRepository)
  {
    $this->subjectTermRepository = $subjectTermRepository;
    $this->termRepository = $termRepository;
    $this->subjectRepository = $subjectRepository;
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
    $currentUser = auth()->user();
    $subjectTerms = $this->subjectTermRepository->getSubjectIdsForTermByUser(config('access.roles_list.protor'), $currentUser);
    return SubjectTermResource::collection($subjectTerms);
//    $subjects = $this->subjectRepository->whereIn('id', $subjectIds)->get();
    return new SubjectResource($subjects);
  }

}
