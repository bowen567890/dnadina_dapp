<?php


namespace App\Http\Controllers\Api\V1\Common;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\LangService;
use App\Http\Resources\Common\LanguageResource;
use App\Http\Controllers\Api\ApiController;

class LangController extends ApiController
{

    /**
     *  获取语言包配置
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function get(Request $request): JsonResponse
    {
        try {
            return $this->response([
                  'default'  => Setting('default_lang'),
                  'pkg'   => LanguageResource::collection(LangService::getService()->getLangList()),
                  'i18n'  => LangService::getService()->getIn18n(),
              ]);
        } catch (\Exception $exception) {
            return $this->__responseError($exception->getMessage(),$exception->getCode());
        }
    }


}


