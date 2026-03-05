<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Api\ApiController;
use App\Models\DestroyLog;
use App\Models\WebsiteAnalyze;
use Illuminate\Support\Facades\Cache;

class MainPageController extends ApiController
{


    public function info()
    {
        try {
            //销毁池数量
            $destroy_power_num = Cache::remember('destroy_power_num',3600,function(){
                return GetAddressNumber("0x000000000000000000000000000000000000dead");
            });
            //流动池数量
            $flow_pool_num = Cache::remember('flow_pool_num',3600,function(){
                $baseNum = 458122.92;
                $now = time();
                $count = floor(($now-1749139200)/86400);
                return bcadd($baseNum,$count*33000,2);
            });
            //节点剩余购买时间
            $now = time();
            $end = 1749306120;
            $last_second = $end - $now;
            return $this->response([
                'destroy_power_num' => $destroy_power_num,
                'flow_pool_num' => $flow_pool_num,
                'last_second' => $last_second,
            ]);
        } catch (\Exception $exception) {
            return $this->__responseError($exception->getMessage(),$exception->getCode());
        }
    }

}
