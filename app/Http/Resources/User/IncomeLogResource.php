<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class IncomeLogResource  extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'amount_type' => $this->amount_type,
            'total' => MoneyFormat($this->total),
            'type' => $this->type,
            'remark' => $this->remark,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }


}
