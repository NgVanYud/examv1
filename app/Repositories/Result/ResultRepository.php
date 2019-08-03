<?php

namespace App\Repositories\Result;


use App\Models\Result;
use App\Repositories\BaseRepository;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Term\TermRepository;
use App\Repositories\User\StudentRepository;

class ResultRepository extends BaseRepository
{
  public $subjectRepository;
  public $termRepository;
  public $studentRepository;

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
                              SubjectRepository $subjectRepository,
                              TermRepository $termRepository,
                              StudentRepository $studentRepository
                              ) {
    $this->subjectRepository = $subjectRepository;
    $this->termRepository = $termRepository;
    $this->studentRepository = $studentRepository;
    $this->makeModel();
  }


  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Result::class;
  }

}
