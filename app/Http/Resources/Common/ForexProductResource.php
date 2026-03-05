<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;

class ForexProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => LocalDataGet($this->name),
            'symbol' => $this->symbol,
            'last_price' => Money4Format($this->last_price),
            'volume' => $this->volume,
            'profit' => $this->profit,
        ];
    }
}
