<?php

namespace App\Admin\Renderable;

use App\Models\LoanRepayment;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class LoanRepaymentTable extends LazyRenderable
{
    public function grid(): Grid
    {
        return Grid::make(new LoanRepayment(), function (Grid $grid) {
            $grid->model()->orderBy('id','asc');
//             $grid->column('id', 'ID')->sortable();
            $grid->column('period', '期数');
            $grid->column('status', '状态')->display(function ($status) {
                switch ($status) {
                    case 0:
                        return '<span class="badge badge-secondary">待还款</span>';
                    case 1:
                        return '<span class="badge badge-warning">已还款</span>';
                    case 2:
                        return '<span class="badge badge-success">已逾期</span>';
                    default:
                        return '<span class="badge badge-secondary">未知</span>';
                }
            });
            $grid->column('due_amount', '应还本金');
            $grid->column('service_fee', '服务费用');
            $grid->column('repay_amount', '实际应还');
//             $grid->column('btime', '开始时间');
            $grid->column('overdue_days', '逾期天数');
            $grid->column('overdue_fee', '逾期费用');
            $grid->column('etime', '应还时间');
            $grid->column('repay_time', '还款时间');
            
            $grid->paginate(10);
            $grid->disableActions();
            $grid->disableCreateButton();
            
            $loan_id = $this->payload['loan_id'] ?? null;
            
            if ($loan_id) {
                $grid->model()->where('loan_id', (int)$loan_id);
            }
            
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('status', '状态')->select([
                    0 => '待还款',
                    1 => '已还款', 
                    2 => '已逾期',
                ])->width(3);
                $filter->equal('period', '期数')->width(3);
                $filter->between('etime', '时间范围')->date()->width(4);
            });
        });
    }
}
