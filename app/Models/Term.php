<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Term extends Model
{
  use Uuid, SoftDeletes;

  const ACTIVE_CODE = 1;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'begin', 'end', 'uuid', 'code', 'active', 'is_done'
  ];


  protected $dates = ['deleted_at', 'begin', 'end'];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'active' => 'boolean',
    'is_done' => 'boolean',
  ];

  public function subjects()
  {
    return $this->belongsToMany(Subject::class)->withPivot('original_exam_num', 'progression', 'id', 'quiz_format');
  }

  public function quizs()
  {
    return $this->belongsToMany(Quiz::class, 'quiz_term', 'subject_term_id', 'quiz_id');
  }

  public function subjectTerms() {
    return $this->hasMany('App\Models\SubjectTerm', 'term_id');
  }

  public function isActive()
  {
    return $this->active;
  }

  public function isDeactive()
  {
    return !$this->isActive();
  }

  public function scopeActived(Builder $query)
  {
    return $query->where('active', '=', 1);
  }

  public function scopeDeactived(Builder $query)
  {
    return $query->where('active', '=', 0);
  }

  public function scopeDone(Builder $query): Builder
  {
    return $query->where('is_done', '=', 1);
  }

  public function scopeNotDone(Builder $query)
  {
    return $query->where('is_done', '=', 0);
  }
}
