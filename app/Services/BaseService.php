<?php
namespace App\Services;

use Illuminate\Support\Facades\App;
use App\Traits\AppBase;

class BaseService
{
    use AppBase;

    /**
     * 获取服务实例，如果没有则创建
     *
     * @return object
     */
    public static function getService(): object
    {
        $serviceClass = static::class; // 使用当前类名

        // 判断服务实例是否已经被创建过
        if (!App::bound($serviceClass)) {
            // 如果没有，创建并绑定到服务容器
            App::singleton($serviceClass, function () use ($serviceClass) {
                return new $serviceClass();
            });
        }

        // 使用 App::make() 获取服务实例
        return App::make($serviceClass); // 这里是调用 App::make()
    }

}
