<?php

namespace App\Models;

use App\Models\Traits\ResultAttributes;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
  use ResultAttributes;

  protected $fillable = [
    'student_code',
    'first_name',
    'last_name',
    'answer',
    'questions_total',
    'subject_term_id',
    'quiz_id',
    'detail'
  ];

  protected $appends = [
    'score'
  ];

  protected $casts = [
    'answer' => 'Integer',
    'questions_total' => 'Integer'
  ];

  public function quiz()
  {
    return $this->belongsTo(Quiz::class, 'quiz_id');
  }

  public function subjectTerm()
  {
    return $this->belongsTo(SubjectTerm::class, 'subject_term_id');
  }
}
