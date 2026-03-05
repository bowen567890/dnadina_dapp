<?php

namespace App\Admin\Controllers;

use App\Models\DhtLockOrder;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;

class DhtLockOrderController extends AdminController
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
    
    public $statusArr = [0=>'待释放', 1=>'释放中', 2=>'已完结'];
    protected function grid()
    {
        return Grid::make(DhtLockOrder::with(['user']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('user_id');
            $grid->column('user.address', '用户地址');
            $grid->column('status')->using($this->statusArr)->label('success');
            $grid->column('total_day');
            $grid->column('wait_day');
            $grid->column('total_num');
            $grid->column('wait_num');
            $grid->column('daily_num');
            $grid->column('over_num');
//             $grid->column('ordernum');
            $grid->column('created_at');
            
//             $grid->column('updated_at')->sortable();
            // $grid->setActionClass(Grid\Displayers\Actions::class); // 行操作按钮显示方式 图标方式
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableEditButton();
                        $grid->disableDeleteButton();
                        $grid->disableActions();
            $grid->scrollbarX();    			//滚动条
            $grid->paginate(10);				//分页
            
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal('user_id');
                $filter->equal('user.address', '用户地址');
                $filter->equal('status')->select($this->statusArr);
                $filter->between('created_at','创建时间')->date();
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
        return Show::make($id, new DhtLockOrder(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('status');
            $show->field('total_day');
            $show->field('wait_day');
            $show->field('total_num');
            $show->field('wait_num');
            $show->field('daily_num');
            $show->field('over_num');
            $show->field('ordernum');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new DhtLockOrder(), function (Form $form) {
            $form->display('id');
            $form->text('user_id');
            $form->text('status');
            $form->text('total_day');
            $form->text('wait_day');
            $form->text('total_num');
            $form->text('wait_num');
            $form->text('daily_num');
            $form->text('over_num');
            $form->text('ordernum');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
