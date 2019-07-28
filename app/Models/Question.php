<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{

  use SoftDeletes;

  const ACTIVE_CODE = 1;
  const INACTIVE_CODE = 0;
  const PUBLISHED_CODE = 1;
  const UNPUBLISHED_CODE = 0;

  protected $fillable = [ 'id', 'content', 'is_actived', 'chapter_id', 'subject_id', 'is_published' ];

  protected $casts = [
    'is_actived' => 'boolean',
    'is_published' => 'boolean'
  ];

  protected $dates = [ 'deleted_at' ];

  public function getRouteKeyName()
  {
    return 'id';
  }

  public function scopePublish($query, $status = true)
  {
    return $query->where('is_published', $status);
  }

  public function scopeActive($query, $status = true)
  {
    return $query->where('is_actived', $status);
  }

  public function isPublished() {
    return $this->is_published;
  }

  public function options() {
    return $this->hasMany(Option::class, 'question_id', 'id');
  }

  public function chapter() {
    return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
  }

  public function subject() {
    return $this->belongsTo(Subject::class);
  }

  public function quizs() {
    return $this->belongsToMany(Quiz::class,
      'question_quiz',
      'question_id',
      'quiz_id')
      ->withPivot('order');
  }
}
