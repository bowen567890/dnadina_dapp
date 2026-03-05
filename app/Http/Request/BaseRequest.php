<?php

namespace App\Http\Request;

use App\Services\AppService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class BaseRequest extends FormRequest
{

    /**
     * 是否授权请求
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 参数校验失败统一返回
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new HttpResponseException(response()->json([
            'code'=> 422,
            'local' => AppService::getService()->local(),
            'errors'=> '参数交易失败',
            'message'=> $validator->errors()->first()
        ])));
    }

    /**
     * 处理授权失败的返回格式
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'code' => Response::HTTP_FORBIDDEN, // 403
            'message' => '权限不足，无法执行此操作',
        ], Response::HTTP_FORBIDDEN));
    }

}
