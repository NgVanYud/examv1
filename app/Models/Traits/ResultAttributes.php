<?php

namespace App\Models\Traits;

use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 2/14/19
 * Time: 10:21 PM
 */
trait ResultAttributes
{

  public function getCreatedAtAttribute()
  {
    return Carbon::parse($this->attributes['created_at'])->diffForHumans();
  }

  public function getFullNameAttribute()
  {
    return $this->last_name . " " . $this->first_name;
  }

  public function setDetailAttribute($value)
  {
    $this->attributes['detail'] = json_encode($value);
  }

  public function getDetailAttribute($value)
  {
    return json_decode($value, true);
  }

  public function getScoreAttribute($code)
  {
    return round((10/($this->questions_total))*$this->answer, 1, PHP_ROUND_HALF_EVEN);
  }

}
