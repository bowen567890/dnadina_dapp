<?php

namespace App\Http\Resources\Recharge;

use Illuminate\Http\Resources\Json\JsonResource;

class RechargeListResource  extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'order_no' => $this->order_no,
            'type' => $this->type,
            'nums' => $this->nums,
            'coin' => $this->coin,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }


}
