<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class RankConfig extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'rank_config';

    /**
     * 确保 name 始终返回数组，避免 embeds 表单在 name 为 null 时报错
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value === null || $value === '') {
                    return [];
                }
                if (is_array($value)) {
                    return $value;
                }
                $decoded = json_decode($value, true);
                return is_array($decoded) ? $decoded : [];
            },
        );
    }
    
    
    /**
     * 设置缓存
     */
    public static function SetListCache()
    {
        $key = 'RankConfigList';
        $MyRedis = new MyRedis();
        $list = self::query()
            ->orderBy('lv', 'asc')
            ->get()
            ->toArray();
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
        $key = 'RankConfigList';
        $MyRedis = new MyRedis();
        $list = $MyRedis->get_key($key);
        if (!$list) {
            return self::SetListCache();
        } else {
            return unserialize($list);
        }
    }
}
