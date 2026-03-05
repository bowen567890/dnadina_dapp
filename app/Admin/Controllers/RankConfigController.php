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
use App\Models\Base\Language;

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

  public $lvArr = [0=>'',1=>'联创'];
    protected function grid()
    {
        return Grid::make(new RankConfig(), function (Grid $grid) {
            $grid->column('id')->sortable();
            
//             $grid->column('lv');
            $grid->column('lv')->using($this->lvArr)->label('success');
            $grid->column('zhi_yeji', '直推业绩');
            $grid->column('image', '图标')->image(env('APP_URL').'/uploads/', 50, 50);
//             $grid->column('team_power');
//             $grid->column('rate');
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
            $language = Language::query()->get();
            $form->embeds('name', '等级名称', function (Form\EmbeddedForm $form) use ($language) {
                foreach ($language as $lang) {
                    $lang->show ? $form->text($lang->slug, $lang->name)->required() : $form->hidden($lang->slug, $lang->name);
                }
            });
            $form->number('zhi_yeji', '直推业绩')->min(0)->default(0)->required();
            $form->image('image', '图标')->uniqueName()->maxSize(10240)->accept('jpg,png,jpeg')->autoUpload()->removable(false);
//             $form->number('team_power')->min(0)->default(0)->required();
//             $form->decimal('rate')->required()->help('收益比率(0.1=10%)');
            
            $form->saving(function (Form $form)
            {
                $id = $form->getKey();
                
                
//                 $rate = @bcadd($form->rate, '0', 2);
//                 if (bccomp($rate, '1', 2)>0 || bccomp('0', $rate, 2)>0) {
//                     return $form->response()->error('收益比率不正确');
//                 }
//                 //                 $lv = intval($form->lv);
//                 //                 if ($form->isCreating()) {
//                 //                     // 也可以这样获取自增ID
//                 //                     $res = RankConfig::query()->where('lv', $lv)->first();
//                 //                     if ($res) {
//                 //                         return $form->response()->error('等级已存在');
//                 //                     }
//                 //                 }
//                 //                 if ($form->isEditing()) {
//                 //                     $res = RankConfig::query()->where('lv', $lv)->first();
//                 //                     if ($res) {
//                 //                         if ($res->id!=$id){
//                 //                             return $form->response()->error('等级已存在');
//                 //                         }
//                 //                     }
//                 //                 }
//                 $form->rate = $rate;
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
