<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectTerm extends Model
{
    protected $table = 'subject_term';

    protected $fillable = ['original_exam_num', 'progression', 'subject_id', 'term_id'];

    public function studentTerms() {
      return $this->hasMany(StudentTerm::class, 'subject_term_id');
    }

    public function quizs() {
      return $this->hasMany(Quiz::class, 'subject_term_id');
    }
}
