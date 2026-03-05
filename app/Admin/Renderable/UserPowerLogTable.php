<?php

namespace App\Admin\Renderable;


use App\Enums\IncomeAmountTypeEnum;
use App\Enums\IncomeTypeEnum;
use App\Models\IncomeLogModel;
use App\Models\UsersPowerLog;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;


class UserPowerLogTable extends LazyRenderable
{


    public function grid(): Grid
    {
        $model = UsersPowerLog::useShardingTable($this->payload['user_id']);
        return Grid::make($model->query()->with(['user'])->where('user_id',$this->payload['user_id']), function (Grid $grid) {
            $grid->model()->orderByDesc('id');
            $grid->number();
            $grid->column('user.address', '用户');
            $grid->column('power_type','类型')->using([1=>'购买节点',2=>'购买矿机',3=>'算力到期',4=>'激活算力'])->badge();
            $grid->column('before','操作前');
            $grid->column('power','操作算力');
            $grid->column('after','操作后');
            $grid->column('created_at');
            $grid->paginate(15);
            $grid->disableActions();
        });
    }
}
