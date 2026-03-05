<?php

namespace App\Util;

use Illuminate\Support\Facades\Redis;

class RedisLock
{

    /**
     * 获取锁
     * @param String $lockKey 锁标识
     * @param Int $expire 锁过期时间
     * @return Boolean
     */
    public static function lock(string $lockKey, int $expire = 30)
    {
        $lockResult = Redis::setnx($lockKey, $expire);
        if ($lockResult) {
            Redis::expire($lockKey,$expire);
            // 当前进程设置锁成功
            return true;
        }
        return false;
    }

    /**
     * 释放锁
     * @param String $key 锁标识
     * @return Boolean
     */
    public static function unlock(string $key): bool
    {
        return Redis::del($key);
    }


    /**
     * 获取资源
     * @param String $key 锁标识
     * @return Boolean
     */
    public static function get(string $key)
    {
        return Redis::get($key);
    }


    /**
     * 获取资源
     * @param String $key 锁标识
     * @return Boolean
     */
    public static function setex(string $key,$value,$expire = 60): bool
    {
        return Redis::setex($key,$expire,$value);
    }


}
