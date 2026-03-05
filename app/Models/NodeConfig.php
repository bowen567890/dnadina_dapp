<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class NodeConfig extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'node_config';
    
    // Ensure JSON columns are properly cast and not null
    protected $casts = [
        'name' => 'array',
    ];
    
    protected $attributes = [
        // Default to an empty JSON object/array to avoid nulls from legacy rows
        'name' => '[]',
    ];
    
    public function getNameAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        if ($value === null || $value === '') {
            return [];
        }
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }
    
    public function grank(){
        return $this->hasOne(RankConfig::class, 'lv', 'give_rank');
    }
    
    /**
     * 设置缓存
     */
    public static function SetListCache()
    {
        $key = 'NodeConfigList';
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
        $key = 'NodeConfigList';
        $MyRedis = new MyRedis();
        $list = $MyRedis->get_key($key);
        if (!$list) {
            return self::SetListCache();
        } else {
            return unserialize($list);
        }
    }
}
