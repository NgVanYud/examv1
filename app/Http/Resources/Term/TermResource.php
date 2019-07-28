<?php

namespace App\Http\Resources\Term;

use Illuminate\Http\Resources\Json\Resource;


class TermResource extends Resource
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
          'subjects' => $this->resource->subjects
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
            'subjects' => $this->resource->subjects
        ];
    }


}
