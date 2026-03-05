<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;

class ConfigController extends ApiController
{

    /**
     * 获取配置
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        try {
            return $this->response([
                'power_price' => Setting('power_price'),//每算力单价
                'contract_address' => '0x7cAc90e4564998f5380b28d5CE5B2EB497f7Dac8',//充值合约地址
            ]);
        }catch (\Exception $exception) {
            return $this->__responseError($exception->getMessage(),$exception->getCode());
        }
    }

}


