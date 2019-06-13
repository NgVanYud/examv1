<?php

namespace App\Http\Resources\Term;

use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Term\TermRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectTermResource extends JsonResource
{

  public $subjectRepository;
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
  public function __construct($resource, SubjectRepository $subjectRepository, TermRepository $termRepository)
  {
    $this->subjectRepository = $subjectRepository;
    $this->termRepository = $termRepository;
    parent::__construct($resource);
  }

  /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
          'subject' => $this->subjectRepository->getById($this->resource->subject_id),
          'term' => $this->termRepository->getById($this->resource->term_id)
        ]);
    }
}
