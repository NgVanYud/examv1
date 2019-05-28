<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SubjectAttributes;

class Subject extends Model
{
    use Sluggable, SubjectAttributes;

    public $fillable = ['code', 'name', 'slug', 'credit', 'description'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return 'slug';
    }

    public function terms(){
        return $this->belongsToMany(Term::class);
    }
    public function chapters() {
      return $this->hasMany(Chapter::class);
    }

    public function questions() {
      return $this->hasMany(Question::class);
    }

    public function formats() {
      return $this->hasMany(Format::class);
    }

    public function examMakers() {
      return $this->belongsToMany(User::class, 'exams_maker_subject', 'subject_id','exam_maker_id');
    }
}
