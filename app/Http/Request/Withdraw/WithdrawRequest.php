<?php

namespace App\Http\Request\Withdraw;

use App\Http\Request\BaseRequest;

class WithdrawRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'coin_id' => 'required|in:1,2',
            'amount' => 'required|regex:/^\d+(?:\.\d{1,6})?$/',
        ];
    }

    public function messages(){
        return [
            'amount.required' => Lang('请输入提现金额'),
            'amount.regex' => Lang('请输入正确的提现金额'),
        ];
    }
}
