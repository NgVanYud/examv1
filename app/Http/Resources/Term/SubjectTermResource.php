<?php

namespace App\Http\Resources\Term;

use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Term\TermRepository;
use Illuminate\Http\Resources\Json\Resource;

class SubjectTermResource extends Resource
{
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array
   */
  public function toArray($request)
  {
    return array_merge(parent::toArray($request), [
      'subject' => $this->resource->subject,
      'term' => $this->resource->term
    ]);
  }
}
