<?php

namespace App\Http\Request\Common\Feedback;

use App\Http\Request\BaseRequest;

class FeedbackList extends BaseRequest
{

    public function rules(){
        return [
            'page' => 'required|integer',
            'page_size' => 'required|integer',
        ];
    }

}
