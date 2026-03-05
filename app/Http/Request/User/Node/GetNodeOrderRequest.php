<?php

namespace App\Http\Request\User\Node;

use App\Http\Request\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class GetNodeOrderRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'node_id' => 'required|integer',
        ];
    }

}
