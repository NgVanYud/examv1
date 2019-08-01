<?php

namespace App\Models;

use App\Models\Traits\QuizAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes,
      QuizAttributes;

    const ACTIVED_CODE = 1;
    const DEACTIVED_CODE = 0;

    protected $table = 'quizs';

    protected $fillable = ['code', 'subject_term_id', 'detail', 'is_actived', 'question_num', 'timeout', 'answer'];

    protected $casts = [
      'is_actived' => 'boolean',
      'id_detail' => 'array',
      'detail' => 'array'
    ];

    public function term() {
      return $this->belongsTo(SubjectTerm::class, 'subject_term_id');
    }

    public function questions() {
      return $this->belongsToMany(Question::class,
        'question_quiz',
        'quiz_id',
        'question_id')
        ->withPivot('order');;
    }

    public function students() {
      return $this->hasMany('App\Models\Student', 'quiz_id');
    }

}
