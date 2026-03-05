<?php

namespace App\Admin\Renderable;


use App\Models\User;
use Dcat\Admin\Support\LazyRenderable;
use App\Models\RankConfig;

class RenderableUserInfo extends LazyRenderable
{

    public function render()
    {
        $user_id = $this->payload['user_id'];

        $user = User::query()->find($user_id);
        $parent = User::query()->where('id', $user->parent_id)->first();

        $nodeArr = [0=>'',1=>'启航节点',2=>'飞跃节点',3=>'巅峰节点'];
        $rankArr = RankConfig::query()->orderBy('lv', 'asc')->pluck('name', 'lv')->toArray();
        $rankArr = array_merge([0=>'V0'], $rankArr);
        
        return view('admin.user-info-modal', [
            'user' => $user,
            'parent' => $parent,
            'nodeArr' => $nodeArr,
            'rankArr' => $rankArr,
        ]);
    }
}
