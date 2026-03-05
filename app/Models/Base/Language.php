<?php

namespace App\Models\Base;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use App\Models\MyRedis;

class Language extends Model
{
    use HasDateTimeFormatter,Cachable;

    protected $casts = [
        'required' => 'bool'
    ];
    
    /**
     * 设置缓存
     */
    public static function SetListCache()
    {
        $key = 'LanguageList';
        $MyRedis = new MyRedis();
        $list = self::query()
            ->where('status', true)
            ->orderBy('order','ASC')
            ->get(['name', 'slug','color', 'icon','value']);
        if ($list) {
            $MyRedis->set_key($key, serialize($list));
            return $list;
        }
        if ($MyRedis->exists_key($key)) {
            $MyRedis->del_lock($key);
        }
        return [];
    }
    
    /**
     * 获取缓存
     */
    public static function GetListCache()
    {
        $key = 'LanguageList';
        $MyRedis = new MyRedis();
        $list = $MyRedis->get_key($key);
        if (!$list) {
            return self::SetListCache();
        } else {
            return unserialize($list);
        }
    }
}
