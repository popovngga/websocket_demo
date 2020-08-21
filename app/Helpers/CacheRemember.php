<?php


namespace App\Helpers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CacheRemember
{
    public function __construct(Model $model, string $key)
    {
        Cache::forget($key);
        return Cache::rememberForever($key, function () use ($model) {
            return $model->all();
        });
    }
}
