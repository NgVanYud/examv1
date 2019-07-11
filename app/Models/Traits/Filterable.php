<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait Filterable {
  public function scopeFilter($query, $params) {

    foreach ($params as $field => $value) {
      $method = 'filter'.Str::studly($field);

      if ($value != '') {
        if (method_exists($this, $method)) {
          $this->{$method}($query, $value);
        } else {
          if (!empty($this->filterable) && is_array($this->filterable)) {
            if (key_exists($field, $this->filterable)) {
              $query->where($this->table.'.'.$this->filterable[$field], $value);
            } elseif (in_array($field, $this->filterable)) {
              $query->where($this->table.'.'.$field, $value);
            }
          }
        }
      }
    }
    return $query;
  }
}
