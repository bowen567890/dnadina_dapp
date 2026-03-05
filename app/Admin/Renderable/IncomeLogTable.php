<?php

namespace App\Admin\Renderable;


use App\Enums\IncomeAmountTypeEnum;
use App\Enums\IncomeTypeEnum;
use App\Models\IncomeLogModel;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;


class IncomeLogTable extends LazyRenderable
{


    public function grid(): Grid
    {
        return Grid::make(IncomeLogModel::useShardingTable($this->payload['user_id']), function (Grid $grid) {
            $grid->model()->orderByDesc('id');
            $grid->column('id', 'ID')->sortable();
            $grid->column('user_id', '用户');
            $grid->column('amount_type', '钱包')->display(function ($v) {
                return IncomeAmountTypeEnum::fromValue($v)->description;
            });
            $grid->column('type', '操作')->display(function ($v) {
                return IncomeTypeEnum::fromValue($v)->description;
            });
            $grid->column('before', '变动前余额');
            $grid->column('total', '变动金额')->sortable();
            $grid->column('after', '变动后余额');
            $grid->column('remark', '变动备注');
            $grid->column('created_at')->sortable();
            $grid->paginate(15);
            $grid->disableActions();

            $user_id = $this->payload['user_id'] ?? null;

            $grid->model()->orderBy('id', 'desc');

            if ($user_id) {
                $grid->model()->where('user_id', (int)$user_id);
            }

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('amount_type','钱包类型')->select(IncomeAmountTypeEnum::asSelectArray())->width(3);
                $filter->equal('type','操作类型')->select(IncomeTypeEnum::asSelectArray())->width(3);
                $filter->between('created_at','操作日期')->date()->width(3);
            });

        });
    }
}
