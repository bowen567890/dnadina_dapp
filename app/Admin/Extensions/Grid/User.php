<?php

namespace App\Admin\Extensions\Grid;


use App\Admin\Renderable\IncomeLogTable;
use App\Admin\Renderable\UserPowerLogTable;
use Dcat\Admin\Grid\Displayers\AbstractDisplayer;
use App\Models\User As Users;
use Dcat\Admin\Widgets\Modal;
use App\Admin\Renderable\RenderableUserInfo;
use App\Admin\Renderable\UserWalletLogTable;
use App\Admin\Renderable\UserZhiTable;
use App\Admin\Renderable\UserTeamTable;

class User extends AbstractDisplayer
{
    public function display()
    {
		$UserDetails  = Modal::make()
            ->xl()
            ->title('用户详情')
            ->body(RenderableUserInfo::make()
            ->payload(['user_id' => $this->value]))
            ->button('<button class="btn btn-outline-primary sm-btn">详情</button>');
		
		$userZhiList = Modal::make()
            ->xl()
            ->title('直推列表')
            ->body(UserZhiTable::make()->payload(['user_id' =>$this->value]))
            ->button('<button class="btn btn-outline-primary sm-btn">直推列表</button>');
		
        $userTeamList = Modal::make()
            ->xl()
            ->title('团队列表')
            ->body(UserTeamTable::make()->payload(['user_id' =>$this->value]))
            ->button('<button class="btn btn-outline-primary sm-btn">团队列表</button>');
		
        $user = Users::query()->where('id',$this->value)->first();
		return view('admin.user-info-grid', [
		    'user' => $user, 
		    'model' => $UserDetails,
		    'userZhiList' => $userZhiList,
		    'userTeamList' => $userTeamList
		]);
    }
}
