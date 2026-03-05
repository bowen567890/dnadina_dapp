<?php

namespace App\Http\Request\User\Machine;

use App\Http\Request\BaseRequest;

class UpgradeMachineRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'machine_id' => 'required|integer',
            'pay_type' => 'required|in:1,2',
        ];
    }

}
