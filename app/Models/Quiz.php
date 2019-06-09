<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes;

    protected $table = 'quizs';
    protected $fillable = ['code', 'subject_term_id'];

    public function terms() {
      return $this->belongsTo(SubjectTerm::class, 'subject_term_id');
    }

    public function questions() {
      return $this->belongsToMany(Question::class,
        'question_quiz',
        'quiz_id',
        'question_id')
        ->withPivot('order');;
    }
}
