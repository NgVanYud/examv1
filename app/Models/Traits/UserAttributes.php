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

    /**
     * @return string
     */
    public function getShowButtonAttribute()
    {
        return '<a href="'."#".
            '" data-toggle="tooltip" data-placement="top" title="'.
            __('buttons.general.crud.view').
            '" class="btn btn-info"><i class="fa fa-eye"></i></a>';
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="'.
            "#".
            '" data-toggle="tooltip" data-placement="top" title="'.
            "ten button".
            '" class="btn btn-primary"><i class="fa fa-pencil"></i></a>';
    }

    public function getActionButtonsAttribute() {
        return
            '<div class="btn-group" role="group" aria-label="'.'Me cha'.'">
            '.$this->show_button.'
            '.$this->edit_button.'
		</div>';
    }
}