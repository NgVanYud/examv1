<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
          'options' => $this->resource->options
        ]);
    }

  /**
   * Get any additional data that should be returned with the resource array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array
   */
  public function with($request)
  {
    return [
      'options' => $this->resource->options
    ];
  }
}
