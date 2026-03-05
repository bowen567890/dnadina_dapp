<?php

namespace App\Http\Request\Auth\Login;


use App\Http\Request\BaseRequest;

class LoginOrRegisterRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'address' => 'required',
            'sign_message' => 'required|string',
            'message' => 'required|string',
            'code' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'address.required' => '请选择您的钱包地址',
            'address.regex' => '请选择正确的BSC地址',
            'sign_message.required' => '签名信息不能为空',
            'sign_message.string' => '签名信息必须是字符串',
            'message.required' => '原始消息不能为空',
            'message.string' => '原始消息必须是字符串',
            'code.string' => '邀请码必须是字符串',
        ];
    }

}
