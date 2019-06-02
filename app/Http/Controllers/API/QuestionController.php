<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralException;
use App\Http\Resources\Question\QuestionResource;
use App\Models\Question;
//use App\Repositories\Subject\QuestionRepository;
use App\Repositories\Subject\QuestionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
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
  public function __construct(QuestionRepository $questionRepository)
  {
    $this->questionRepository = $questionRepository;
  }


  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ModelsQuestion  $modelsQuestion
     * @return \Illuminate\Http\Response
     */
    public function show($subjectId, $questionId)
    {
      $question = $this->questionRepository->getById($questionId);
      if($subjectId == $question->subject_id) {
        return new QuestionResource($question);
      } else {
        throw new GeneralException(
          __('exceptions.invalid_data'),
          422
        );
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ModelsQuestion  $modelsQuestion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ModelsQuestion  $modelsQuestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
    }

    public function deactive($subjectId, $questionId) {
      $question = $this->questionRepository->getById($questionId);
      if($question->subject_id == $subjectId) {
        $question->is_actived = Question::INACTIVE_CODE;
        $question->save();
      } else {
        throw new GeneralException(
          __('exceptions.invalid_data'),
          422
        );
      }
    }

    public function active($subjectId, $questionId) {
      $question = $this->questionRepository->getById($questionId);
      if($question->subject_id == $subjectId) {
        $question->is_actived = Question::ACTIVE_CODE;
        $question->save();
      } else {
        throw new GeneralException(
          __('exceptions.invalid_data'),
          422
        );
      }
    }
}
