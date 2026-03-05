<?php

namespace App\Http\Request\User\Income;



use App\Http\Request\BaseRequest;

class IncomeLogRequest extends BaseRequest
{


    public function rules(){
        return [
            'page' => 'integer|nullable',
            'page_size' => 'integer|nullable',
            'amount_type' => 'nullable|string',
            'type' => 'nullable|string',
            'add_type' => 'nullable|in:1,2',
        ];
    }

}
