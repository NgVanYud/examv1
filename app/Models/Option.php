<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
  const CODE_CORRECT = 1;
  const CODE_INCORRECT = 0;
  protected $table = 'options';
  protected $fillable = [
    'content',
    'question_id',
    'is_correct',
  ];

  protected $casts = [
    'is_correct' => 'boolean'
  ];

  public function question() {
    return $this->belongsTo(Question::class, 'question_id', 'id');
  }

  public function scopeCorrect($query, $is_correct = true)
  {
    return $query->where('is_correct', intval($is_correct));
  }
}
