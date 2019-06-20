<?php

namespace App\Models\Traits;

use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 2/14/19
 * Time: 10:21 PM
 */

trait UserAttributes {
    public function setUsernameAttribute($username) {
        $this->attributes['username'] = strtoupper($username);
    }

    public function setFirstNameAttribute($first_name) {
        $this->attributes['first_name'] = ucwords($first_name);
    }

    public function setLastNameAttribute($last_name) {
        $this->attributes['last_name'] = ucwords($last_name);
    }

    public function getCreatedAtAttribute() {
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public function getFullNameAttribute() {
        return $this->last_name." ".$this->first_name;
    }

    public function setCodeAttribute($code) {
      $this->attributes['code'] = strtoupper($code);
    }
}
