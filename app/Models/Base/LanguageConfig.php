<?php

namespace App\Models\Base;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class LanguageConfig extends Model
{
    use HasDateTimeFormatter,Cachable;

    const CACHE_TAG = 'language_config';

    protected $table = 'language_config';

    protected $casts = [
        'content' => 'json',
    ];

    protected $guarded = [];


    public static function AllGroup(): array
    {
        return self::query()->groupBy('group')->select(['group'])->pluck('group', 'group')->toArray();
    }

    protected static function booted()
    {
       /* static::updated(function () {
            \Cache::tags(self::CACHE_TAG)->flush();
        });
        static::created(function () {
            \Cache::tags(self::CACHE_TAG)->flush();
        });*/
    }

}
