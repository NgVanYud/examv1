<?php

namespace App\Models\Traits;


trait QuizAttributes
{
  public function getDetailAttribute($value) {
    return json_decode($value, true);
  }

  public function getAnswerAttributes($value) {
    return json_decode($value, true);
  }

//  public function setQuestionNumAttribute($value) {
//    $examFormat = $this->format;
//    $questionNum = 0;
//    foreach ($examFormat as $key => $value) {
//      $questionNum += $value;
//    }
//    $this->attributes['question_num'] = $questionNum;
//  }

}
