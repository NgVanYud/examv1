<?php

namespace App\Models;

use App\Models\Traits\Uuid;
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
        'name', 'begin', 'end', 'uuid', 'code', 'active'
    ];


    protected $dates = ['deleted_at', 'begin', 'end'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];


    public function isActive() {
        return $this->active;
    }

    public function isDeactive() {
        return !$this->isActive();
    }

    public function subjects() {
        return $this->belongsToMany(Subject::class)->withPivot('original_exam_num', 'progression');
    }

    public function quizs() {
      return $this->belongsToMany(Quiz::class, 'quiz_term', 'subject_term_id', 'quiz_id');
    }
}
