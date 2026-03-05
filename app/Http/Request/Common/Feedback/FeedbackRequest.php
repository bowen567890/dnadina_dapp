<?php

namespace App\Http\Request\Common\Feedback;

use App\Http\Request\BaseRequest;

class FeedbackRequest extends BaseRequest
{

    public function rules(){
        return [
            'title'=>'required|string',
            'desc'=>'required|string',
            'img'=>'required|string|max:100',
        ];
    }

}
