<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UuidTrait
{
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return "string";
    }

    public static function bootUuidTrait()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Str::uuid(36);
            }
        });
    }
}
