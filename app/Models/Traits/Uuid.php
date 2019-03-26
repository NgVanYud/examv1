<?php

namespace App\Models\Traits;

use Webpatser\Uuid\Uuid as PackageUuid;
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 2/17/19
 * Time: 7:49 PM
 */
trait Uuid {
    /**
     * @param $query
     * @param $uuid
     *
     * @return mixed
     */
    public function scopeUuid($query, $uuid)
    {
        return $query->where($this->getUuidName(), $uuid);
    }
    /**
     * @return string
     */
    public function getUuidName()
    {
        return property_exists($this, 'uuidName') ? $this->uuidName : 'uuid';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getUuidName()} = PackageUuid::generate(4)->string;
        });
    }
}