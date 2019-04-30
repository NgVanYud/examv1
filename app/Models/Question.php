<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{

  use SoftDeletes;

  const ACTIVE_CODE = 1;
  const INACTIVE_CODE = 0;

  protected $fillable = [ 'content', 'is_actived', 'chapter_id', 'subject_id' ];

  protected $casts = [
    'is_actived' => 'boolean'
  ];

  public function getRouteKeyName()
  {
    return 'id';
  }

  public function scopeActive($query, $status = true)
  {
    return $query->where('is_actived', $status);
  }

  public function options() {
    return $this->hasMany(Option::class, 'question_id', 'id');
  }

  public function chapter() {
    return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
  }
}
