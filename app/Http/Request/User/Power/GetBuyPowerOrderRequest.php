<?php

namespace App\Http\Request\User\Power;

use App\Http\Request\BaseRequest;

class GetBuyPowerOrderRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'num' => 'required|integer|min:1',
        ];
    }

}
