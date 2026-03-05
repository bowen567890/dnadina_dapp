<?php

namespace App\Http\Request\Recharge;

use App\Http\Request\BaseRequest;

class RechargeListRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'page' => 'integer|nullable',
            'page_size' => 'integer|nullable',
        ];
    }
}
