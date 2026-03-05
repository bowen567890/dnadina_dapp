<?php

namespace App\Admin\Controllers;

use App\Models\RankConfig;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\JsonResponse;
use App\Models\MyRedis;

class RankConfigController extends AdminController
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
        return Grid::make(new RankConfig(), function (Grid $grid) {
            $grid->column('id')->sortable();
//             $grid->column('name');
//             $grid->column('lv');
            $grid->column('lv')->display(function() {
                return "<span class='label' style='background:#21b978'>V{$this->lv}</span>";
            });
//             $grid->column('self_power');
//             $grid->column('team_power');
            $grid->column('rate');
//             $grid->column('created_at');
            $grid->column('updated_at')->sortable();
            // $grid->setActionClass(Grid\Displayers\Actions::class); // 行操作按钮显示方式 图标方式
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete(); //  禁用删除
                // $actions->disableEdit();   //  禁用修改
                $actions->disableQuickEdit(); //禁用快速修改(弹窗形式)
                $actions->disableView(); //  禁用查看
            });
            
            $grid->disableCreateButton();
        });
    }
    protected function form()
    {
        return Form::make(new RankConfig(), function (Form $form) {
            $form->display('id');
            $form->display('name');
//             $form->number('self_power')->min(0)->default(0)->required();
//             $form->number('team_power')->min(0)->default(0)->required();
            $form->decimal('rate')->required()->help('收益比率(0.1=10%)');
            
            $form->saving(function (Form $form)
            {
                $id = $form->getKey();
                
                $key = "RankConfig:update:{$id}";
                //修改等级配置需要24小时
                $MyRedis = new MyRedis();
                if ($MyRedis->exists_key($key)) {
                    $lastTime = $MyRedis->get_key($key);
                    return $form->response()->error("修改等级配置需间隔24小时,上次修改时间$lastTime");
                }
                
                $rate = @bcadd($form->rate, '0', 2);
                if (bccomp($rate, '1', 2)>0 || bccomp('0', $rate, 2)>0) {
                    return $form->response()->error('收益比率不正确');
                }
                //                 $lv = intval($form->lv);
                //                 if ($form->isCreating()) {
                //                     // 也可以这样获取自增ID
                //                     $res = RankConfig::query()->where('lv', $lv)->first();
                //                     if ($res) {
                //                         return $form->response()->error('等级已存在');
                //                     }
                //                 }
                //                 if ($form->isEditing()) {
                //                     $res = RankConfig::query()->where('lv', $lv)->first();
                //                     if ($res) {
                //                         if ($res->id!=$id){
                //                             return $form->response()->error('等级已存在');
                //                         }
                //                     }
                //                 }
                $form->rate = $rate;
                
                $lastTime = date('Y-m-d H:i:s');
                $MyRedis->setex($key, 86300, $lastTime);
            });
        
            $form->saved(function (Form $form, $result) 
            {
                RankConfig::SetListCache();
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
        return JsonResponse::make()->success('删除成功')->location('rank_config');
    }
}
