<?php

namespace App\Admin\Controllers;

use App\Models\FundPoolLog;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;

class FundPoolLogController extends AdminController
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
        return Grid::make(new FundPoolLog(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('date');
            $grid->column('type');
            $grid->column('op_type');
            $grid->column('amount');
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
        return Show::make($id, new FundPoolLog(), function (Show $show) {
            $show->field('id');
            $show->field('date');
            $show->field('type');
            $show->field('op_type');
            $show->field('amount');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new FundPoolLog(), function (Form $form) {
            $form->display('id');
            $form->text('date');
            $form->text('type');
            $form->text('op_type');
            $form->text('amount');
        });
    }
}
