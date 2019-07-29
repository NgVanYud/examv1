<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Role;

class SubjectTerm extends Model
{
  protected $table = 'subject_term';

  protected $casts = [
    'is_actived' => 'boolean',
    'is_done' => 'boolean',
//    'quiz_format' => 'array'
  ];

  protected $fillable = ['original_exam_num', 'progression', 'subject_id', 'term_id', 'is_actived', 'is_done', 'quiz_format'];

  public function studentTerms()
  {
    return $this->hasMany(StudentTerm::class, 'subject_term_id');
  }

  public function protors()
  {
    return $this->belongsToMany(User::class, 'protor_term', 'subject_term_id', 'protor_id');
  }

  public function quizs()
  {
    return $this->hasMany(Quiz::class, 'subject_term_id');
  }

  public function term()
  {
    return $this->belongsTo('App\Models\Term', 'term_id');
  }

  public function subject()
  {
    return $this->belongsTo('App\Models\Subject', 'subject_id');
  }

  public function scopeDone(Builder $query): Builder
  {
    return $query->where('is_done', '=', 1);
  }

  public function scopeNotDone(Builder $query)
  {
    return $query->where('is_done', '=', 0);
  }

  public function scopeActived(Builder $query)
  {
    return $query->where('is_actived', '=', 1);
  }

  public function scopeDeactived(Builder $query)
  {
    return $query->where('is_actived', '=', 0);
  }
}
