<?php

namespace App\Http\Controllers\API;

use App\Exports\UsersExport;
use App\Http\Requests\StoreSettingSubjectTermRequest;
use App\Http\Resources\Term\SubjectTermResource;
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
  public function __construct(SubjectTermRepository $subjectTermRepository, TermRepository $termRepository)
  {
    $this->subjectTermRepository = $subjectTermRepository;
    $this->termRepository = $termRepository;
  }

  public function index() {
  }

  public function show(Request $request) {
    $termDetail = $this->subjectTermRepository
      ->where('term_id', $request->term_id)
      ->where('subject_id', $request->subject_id)
      ->get();
    return new SubjectTermResource($termDetail);
  }

  public function setting(StoreSettingSubjectTermRequest $request, $termId, $subjectId) {
    return DB::transaction(function() use ($request, $termId, $subjectId) {
      $studentData = $this->subjectTermRepository->storeSetting($termId, $subjectId, $request->all());
      return $studentData;
    });
  }

  public function exportUserData($data, $filename = 'ds_user') {
    return Excel::download(new UsersExport($data), $filename.'.xlsx');
  }

}
