<?php

namespace App\Admin\Renderable;


use App\Enums\IncomeAmountTypeEnum;
use App\Enums\IncomeTypeEnum;
use App\Models\IncomeLogModel;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;
use App\Models\User;
use App\Models\RankConfig;


class UserZhiTable extends LazyRenderable
{
    public $nodeArr = [0=>'',1=>'启航节点',2=>'飞跃节点',3=>'巅峰节点'];
    public $rankArr = [];
    public function __construct() {
        $rankArr = RankConfig::query()->orderBy('lv', 'asc')->pluck('name', 'lv')->toArray();
        $this->rankArr = array_merge([0=>'V0'], $rankArr);
    }
    
    public function grid(): Grid
    {
        return Grid::make(new User(), function (Grid $grid) {
            $grid->column('id', 'ID')->sortable();
            $grid->column('address', '地址');
            $grid->column('rank', '团队等级')->using($this->rankArr)->label('success');
            $grid->column('node_rank', '节点等级')->using($this->nodeArr)->label();
            $grid->column('created_at','注册日期')->display(function ($value) {
                return $value->toDateTimeString();
            });
            
            $grid->paginate(10);
            $grid->disableActions();

            $user_id = $this->payload['user_id'] ?? null;

            $grid->model()->orderBy('id', 'desc');

            if ($user_id) {
                $grid->model()->where('parent_id', (int)$user_id);
            }

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('address','地址')->width(3);
                $filter->between('created_at','注册日期')->date()->width(3);
            });

        });
    }
}
