<?php

namespace App\Api\Core\Traits;

use Ramsey\Uuid\Uuid;

trait UuidTrait
{

    public function scopeUuid($query, $uuid)
    {
        return $query->where($this->getUuidName(), $uuid);
    }

    public function getUuidName()
    {
        return property_exists($this, 'uuidName') ? $this->uuidName : 'uuid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getUuidName()} = Uuid::uuid4();
        });
    }
}
