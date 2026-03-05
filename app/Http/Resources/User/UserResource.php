<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'invite_code' => $this->code,
            'address' => $this->address,
            'avatar' => ImageUrl($this->avatar),
            'zhi_num' => $this->zhi_num,
            'team_num' => $this->team_num,
            'zhi_performance' => $this->zhi_performance,
            'team_performance' => $this->team_performance,
            'total_performance' => $this->total_performance,
            'created_at' => $this->formatted_created_at,
        ];
    }


}
