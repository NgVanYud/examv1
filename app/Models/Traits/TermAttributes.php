<?php

trait TermAttributes {
    public function setNameAttribute($name) {
        $this->attributes['name'] = strtolower($name);
    }
}
