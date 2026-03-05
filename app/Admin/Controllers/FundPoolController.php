<?php

namespace App\Admin\Controllers;

use App\Models\FundPool;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\JsonResponse;

class FundPoolController extends AdminController
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
    
    public $typeArr = [
        1=>'交易所池子',
        2=>'节点分红池',
    ];
    protected function grid()
    {
        return Grid::make(new FundPool(), function (Grid $grid) {
//             $grid->column('id')->sortable();
            $grid->column('type')->using($this->typeArr)->label();
            $grid->column('amount');
            
            $grid->model()->where('type', '<>', 1);
//             $grid->column('created_at');
//             $grid->column('updated_at')->sortable();
            // $grid->setActionClass(Grid\Displayers\Actions::class); // 行操作按钮显示方式 图标方式
//             $grid->actions(function (Grid\Displayers\Actions $actions) {
//                 // $actions->disableDelete(); //  禁用删除
//                 // $actions->disableEdit();   //  禁用修改
//                 // $actions->disableQuickEdit(); //禁用快速修改(弹窗形式)
//                 // $actions->disableView(); //  禁用查看
//             });
//             $grid->filter(function (Grid\Filter $filter) {
//                 $filter->equal('id');
        
//             });
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableDeleteButton();
            $grid->disableActions();
        });
    }

//     protected function detail($id)
//     {
//         return Show::make($id, new FundPool(), function (Show $show) {
//             $show->field('id');
//             $show->field('type');
//             $show->field('amount');
//             $show->field('created_at');
//             $show->field('updated_at');
//         });
//     }

//     protected function form()
//     {
//         return Form::make(new FundPool(), function (Form $form) {
//             $form->display('id');
//             $form->text('type');
//             $form->text('amount');
        
//             $form->display('created_at');
//             $form->display('updated_at');
//         });
//     }

    /**
     * 删除
     */
    public function destroy($id)
    {
        return JsonResponse::make()->success('删除成功')->location('fund_pool');
    }
}
