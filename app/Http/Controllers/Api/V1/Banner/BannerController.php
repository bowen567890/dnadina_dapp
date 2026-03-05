<?php

namespace App\Http\Controllers\Api\V1\Banner;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Hosting\HostingConfigResource;
use App\Models\Banner;
use App\Models\PledgeConfig;
use App\Models\UsersHosting;
use Illuminate\Http\JsonResponse;

class BannerController extends ApiController
{

    /**
     *  Banner列表
     * @return JsonResponse
     */
    public function bannerList(): JsonResponse
    {
        try {
            $list = Banner::query()->where('status',1)->select(['banner'])->get();
            foreach ($list as $item) {
                $item->banner = ImageUrl($item->banner);
            }
            return $this->response($list);
        } catch (\Exception $exception) {
            return $this->__responseError($exception->getMessage(),$exception->getCode());
        }
    }

}
