<?php

namespace App\Models;

use App\Models\Auth\User as Authenticable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Authenticable
{
//  use SoftDeletes;

  protected $table = 'students';

  protected $fillable = [
    'last_name', 'first_name', 'username', 'uuid', 'password', 'is_actived', 'code', 'id', 'quiz_id', 'subject_term_id'
  ];

  protected $hidden = [
    'password'
  ];

  protected $dates = [
//    'deleted_at',
    'password_changed_at'
  ];

  public function quiz() {
    return $this->belongsTo('App\Models\Quiz', 'quiz_id');
  }

  public function subjectTerm() {
    return $this->belongsTo('App\Models\SubjectTerm', 'subject_term_id', 'id');
  }
}
