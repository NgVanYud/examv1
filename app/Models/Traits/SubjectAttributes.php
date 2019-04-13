<?php


namespace App\Models\Traits;


trait SubjectAttributes
{
    public function setCodeAttribute($code) {
        $this->attributes['code'] = strtoupper($code);
    }

    public function setNameAttribute($name) {
        $this->attributes['name'] = strtolower($name);
    }

}
