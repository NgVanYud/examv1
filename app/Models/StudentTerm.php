<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTerm extends Model
{
    protected $table = 'student_term';

    protected $fillable = ['student_id', 'subject_term_id', 'quiz_id'];

    public function student() {
      return $this->belongsTo(User::class, 'student_id');
    }

    public function subjectTerm() {
      return $this->belongsTo(SubjectTerm::class, 'subject_term_id');
    }
}
