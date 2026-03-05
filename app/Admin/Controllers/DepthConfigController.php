<?php

namespace App\Admin\Controllers;

use App\Models\DepthConfig;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\JsonResponse;

class DepthConfigController extends AdminController
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
        return Grid::make(new DepthConfig(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('zhi_num');
            $grid->column('depth');
            $grid->column('rate');
//             $grid->column('created_at');
            $grid->column('updated_at')->sortable();
            
            $grid->model()->orderBy('depth','asc');
            // $grid->setActionClass(Grid\Displayers\Actions::class); // 行操作按钮显示方式 图标方式
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                // $actions->disableDelete(); //  禁用删除
                // $actions->disableEdit();   //  禁用修改
                // $actions->disableQuickEdit(); //禁用快速修改(弹窗形式)
                $actions->disableView(); //  禁用查看
            });
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });
        });
    }
    protected function form()
    {
        return Form::make(new DepthConfig(), function (Form $form) {
            $form->display('id');
            $form->number('zhi_num', '直推人数')->default(1)->min(1)->required();
            $form->number('depth','奖励代数')->default(1)->min(1)->required();
            $form->decimal('rate')->required()->placeholder('奖励比率(0.1=10%)')->help('奖励比率(0.1=10%)');
        
            $form->saving(function (Form $form)
            {
                $id = $form->getKey();
                $rate = @bcadd($form->rate, '0', 2);
                if (bccomp($rate, '1', 2)>0 || bccomp('0', $rate, 2)>=0) {
                    return $form->response()->error('奖励比率不正确');
                }
                    
                $zhi_num = intval($form->zhi_num);
                $depth = intval($form->depth);
                if ($form->isCreating()) {
                    // 也可以这样获取自增ID
                    $res = DepthConfig::query()->where('depth', $depth)->first();
                    if ($res) {
                        return $form->response()->error('代数已存在');
                    }
                }
                if ($form->isEditing()) {
                    $res = DepthConfig::query()->where('depth', $depth)->first();
                    if ($res) {
                        if ($res->id!=$id){
                            return $form->response()->error('代数已存在');
                        }
                    }
                }
                $form->rate = $rate;
            });
            
            $form->saved(function (Form $form, $result) {
                DepthConfig::SetListCache();
            });
                
                $form->disableViewButton();
                $form->disableDeleteButton();
                $form->disableResetButton();
                $form->disableViewCheck();
                $form->disableEditingCheck();
                $form->disableCreatingCheck();
        });
    }
    
    
    /**
     * 删除
     */
    public function destroy($id)
    {
//         $res = DepthConfig::query()->where('id', $id)->first();
//         $descDepth = DepthConfig::query()->orderBy('zhi_num', 'desc')->first();
//         if ($descDepth && $descDepth->id!=$res->id) {
//             return JsonResponse::make()->error('只能从最高直推人数开始删除')->location('depth_config');
//         }
        DepthConfig::query()->where('id', $id)->delete();
        DepthConfig::SetListCache();
        return JsonResponse::make()->success('删除成功')->location('depth_config');
    }
}
