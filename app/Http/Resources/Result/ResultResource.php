<?php

namespace App\Http\Resources\Result;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
          'quiz' => $this->resource->quiz,
          'subject' => $this->resource->subjectTerm->subject,
          'term' => $this->resource->subjectTerm->term
        ]);
    }
}
