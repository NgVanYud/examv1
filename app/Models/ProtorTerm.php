<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProtorTerm extends Model
{
    protected $table = 'protor_term';

    protected $fillable = [ 'protor_id', 'subject_term_id' ];
}
