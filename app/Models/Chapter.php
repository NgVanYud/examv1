<?php

namespace App\Models;

use App\Models\Traits\ChapterAttributes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{

  use ChapterAttributes;

  const ACTIVE_STATUS = 1;
  const DEACTIVE_STATUS = 0;

  protected $fillable = [
    'name', 'is_actived', 'slug', 'subject_id'
  ];

  protected $casts = [
    'is_actived' => 'boolean'
  ];

  use Sluggable;

  public function sluggable(): array
  {
    return [
      'slug' => [
        'source' => 'name'
      ]
    ];
  }

  public function subject() {
    return $this->belongsTo(Subject::class, 'subject_id', 'id');
  }

  public function questions() {
    return $this->hasMany(Question::class, 'chapter_id', 'id');
  }

  public function scopeActive($query, $status = true)
  {
    return $query->where('is_actived', $status);
  }

}
