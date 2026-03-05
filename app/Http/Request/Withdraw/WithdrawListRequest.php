<?php

namespace App\Http\Request\Withdraw;

use App\Http\Request\BaseRequest;

class WithdrawListRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'page' => 'integer|nullable',
            'page_size' => 'integer|nullable',
        ];
    }
}
