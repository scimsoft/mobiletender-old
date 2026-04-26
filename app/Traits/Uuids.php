<?php
/**
 * Created by PhpStorm.
 * User: Gerrit
 * Date: 31/10/2020
 * Time: 21:38
 */
namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait Uuids
{

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::uuid4();
        });
    }
}