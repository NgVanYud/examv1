<?php

namespace App\Models\Traits;


trait ChapterAttributes
{

  public function setNameAttribute($name)
  {
    $this->attributes['name'] = strtolower($name);
  }
}
