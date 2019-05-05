<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreExamFormatRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateExamFormatRequest;
use App\Http\Resources\ExamFormat\ExamFormatCollection;
use App\Models\Format;
use App\Repositories\Subject\FormatRepository as ExamFormatRepository;
use App\Repositories\Subject\SubjectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamFormatController extends Controller
{

  public $examFormatRepository;
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
  public function __construct(ExamFormatRepository $examFormatRepository, SubjectRepository $subjectRepository)
  {
    $this->examFormatRepository = $examFormatRepository;
    $this->subjectRepository = $subjectRepository;
  }


  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $subjectId)
    {
      $conditions = [
        'orderBy' => ($request->order_by ? $request->order_by : 'id'),
        'order' => ($request->order ? $request->order : 'asc'),
        'perPage' => ($request->per_page && intval($request->per_page) > 0 ? $request->per_page: 10)
      ];
      return new ExamFormatCollection($this->examFormatRepository
        ->where('subject_id', $subjectId)
        ->orderBy($conditions['orderBy'], $conditions['order'])
        ->paginate($conditions['perPage'])
      );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamFormatRequest $request, $subjectId)
    {
      $formatData = [
        'format' => $request['format'],
        'subject_id' => $subjectId,
        'timeout' => $request['timeout']
      ];
      return $this->examFormatRepository->create($formatData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Format  $examFormat
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $subjectId, $formatId)
    {
        return $this->examFormatRepository->getById($formatId);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Format  $examFormat
     * @return \Illuminate\Http\Response
     */
    public function edit(Format $examFormat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Format  $examFormat
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExamFormatRequest $request, $subjectId, $formatId)
    {
        return $this->examFormatRepository->updateById($formatId, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Format  $examFormat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Format $examFormat)
    {
        //
    }
}
