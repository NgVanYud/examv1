<?php

namespace App\Http\Controllers\API;

use App\Repositories\Term\SubjectTermRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectTermController extends Controller
{
    public $subjectTermRepository;

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
  public function __construct(SubjectTermRepository $subjectTermRepository)
  {
    $this->subjectTermRepository = $subjectTermRepository;
  }
}
