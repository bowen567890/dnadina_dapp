<?php


namespace App\Http\Controllers\Api\V1\Common;


use App\Models\Common\Version;
use App\Enums\VersionEnum;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;

class VersionController extends ApiController
{

    /**
     * 获取最新版本
     * @return JsonResponse
     */
    public function getNewVersion(): JsonResponse
    {
        try {
            $columns = ['title','remark','platform','force','version_code','package_url','hot_version_code','hot_package_url','extend'];
            $android_package =  Version::query()
                ->where('platform', VersionEnum::ANDROID_PACKAGE)
                ->where('status', 1)
                ->orderBy('version_code', 'desc')
                ->first($columns);

            $ios_package =  Version::query()
                ->where('platform', VersionEnum::IOS_PACKAGE)
                ->where('status', 1)
                ->orderBy('version_code', 'desc')
                ->first($columns);
            return $this->response([
                VersionEnum::ANDROID_PACKAGE => $android_package,
                VersionEnum::IOS_PACKAGE => $ios_package,
            ]);
        } catch (\Exception $exception) {
            return $this->__responseError($exception->getMessage(),$exception->getCode());
        }
    }
}
