<?php

namespace App\Models;

use App\Models\Traits\FormatAttributes;
use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
  use FormatAttributes;

  protected $fillable = [ 'format', 'timeout', 'subject_id', 'question_num' ];

    public function subject() {
      return $this->belongsTo(Subject::class);
    }

}
