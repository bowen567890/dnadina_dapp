<?php

namespace App\Http\Request\User\Power;

use App\Http\Request\BaseRequest;

class BalanceBuyMachineRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'num' => 'required|integer|min:1',
        ];
    }

}
