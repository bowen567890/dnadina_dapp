<?php

namespace App\Http\Resources\Notice;

use App\Models\Common\Notice;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'title'   => $this->when(!in_array('title', $this->getHidden()), LocalDataGet($this->title)),
            'content' => $this->when(!in_array('content', $this->getHidden()), LocalDataGet($this->content)),
            'order'   => $this->order,
            'ispop'   => $this->ispop,
        ];
    }
}
