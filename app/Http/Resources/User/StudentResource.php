<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
      'quiz' => $this->resource->quiz
    ]);
  }

  /**
   * Get any additional data that should be returned with the resource array.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  public function with($request)
  {
    return [
      'roles' => $this->resource->getRoleIds(),
      'permissions' => $this->resource->getPermissionIds(),
    ];
  }
}
