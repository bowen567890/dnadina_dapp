<?php

namespace App\Http\Request\Auth\Login;

use App\Http\Request\BaseRequest;

class CheckRegisterRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'address' => 'required',
        ];
    }

}
