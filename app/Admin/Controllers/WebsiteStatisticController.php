<?php

namespace App\Admin\Controllers;

use App\Models\WebsiteStatistic;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;

class WebsiteStatisticController extends AdminController
{
    /**
     * page index
     */
    public function index(Content $content)
    {
        return $content
            ->header('列表')
            ->description('全部')
            ->breadcrumb(['text'=>'列表','url'=>''])
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new WebsiteStatistic(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('register_num');
            $grid->column('recharge_usdt');
            $grid->column('recharge_usdt_node');
            $grid->column('recharge_usdt_contract');
            $grid->column('withdraw_usdt');
            $grid->column('withdraw_usdt_issue');
            $grid->column('withdraw_btc');
            $grid->column('withdraw_btc_issue');
            $grid->column('withdraw_rwa');
            $grid->column('withdraw_rwa_issue');
            $grid->column('income_usdt');
            $grid->column('income_btc');
            $grid->column('income_rwa');
            // $grid->setActionClass(Grid\Displayers\Actions::class); // 行操作按钮显示方式 图标方式
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                // $actions->disableDelete(); //  禁用删除
                // $actions->disableEdit();   //  禁用修改
                // $actions->disableQuickEdit(); //禁用快速修改(弹窗形式)
                // $actions->disableView(); //  禁用查看
            });
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new WebsiteStatistic(), function (Show $show) {
            $show->field('id');
            $show->field('register_num');
            $show->field('recharge_usdt');
            $show->field('recharge_usdt_node');
            $show->field('recharge_usdt_contract');
            $show->field('withdraw_usdt');
            $show->field('withdraw_usdt_issue');
            $show->field('withdraw_btc');
            $show->field('withdraw_btc_issue');
            $show->field('withdraw_rwa');
            $show->field('withdraw_rwa_issue');
            $show->field('income_usdt');
            $show->field('income_btc');
            $show->field('income_rwa');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new WebsiteStatistic(), function (Form $form) {
            $form->display('id');
            $form->text('register_num');
            $form->text('recharge_usdt');
            $form->text('recharge_usdt_node');
            $form->text('recharge_usdt_contract');
            $form->text('withdraw_usdt');
            $form->text('withdraw_usdt_issue');
            $form->text('withdraw_btc');
            $form->text('withdraw_btc_issue');
            $form->text('withdraw_rwa');
            $form->text('withdraw_rwa_issue');
            $form->text('income_usdt');
            $form->text('income_btc');
            $form->text('income_rwa');
        });
    }
}
