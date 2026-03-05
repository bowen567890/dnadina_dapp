<?php

namespace App\Admin\Extensions\Grid;


use App\Admin\Renderable\IncomeLogTable;
use Dcat\Admin\Grid\Displayers\AbstractDisplayer;
use App\Models\User As Users;
use Dcat\Admin\Widgets\Modal;
use App\Admin\Renderable\RenderableUserInfo;

class BaseUser extends AbstractDisplayer
{
    public function display()
    {
		$UserDetails  = Modal::make()->xl()->title('用户详情')->body(RenderableUserInfo::make()->payload(['user_id' => $this->value]))->button('<button class="btn btn-outline-primary sm-btn">详情</button>');
		$WalletDetails = Modal::make()->xl()->title('资金明细')->body(IncomeLogTable::make()->payload(['user_id' =>$this->value]))->button('<button class="btn btn-outline-primary sm-btn">流水</button>');
		$user = Users::query()->where('id',$this->value)->with(['balance1','balance2'])->first();
		return view('admin.user-base-grid', ['user_id'=>$this->value,'user' => $user, 'model' => $UserDetails, 'logModel' => $WalletDetails]);
    }
}
