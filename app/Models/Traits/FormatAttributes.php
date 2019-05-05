<?php

namespace App\Models\Traits;


trait FormatAttributes
{
  public function getFormatAttribute($value) {
    return json_decode($value, true);
  }

  public function setFormatAttribute($value) {
    $this->attributes['format'] = json_encode($value);
  }

  public function getQuestionNumAttribute() {
    $examFormat = $this->format;
    $questionNum = 0;
    foreach ($examFormat as $key => $value) {
      $questionNum += $value;
    }
    return $questionNum;
  }

  public function setQuestionNumAttribute($value) {
    $examFormat = $this->format;
    $questionNum = 0;
    foreach ($examFormat as $key => $value) {
      $questionNum += $value;
    }
    $this->attributes['question_num'] = $questionNum;
  }

}
