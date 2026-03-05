<?php

namespace App\Admin\Controllers\Cms;

use App\Admin\Repositories\Banner;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;

class BannerController extends AdminController
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
        return Grid::make(new Banner(), function (Grid $grid) {
            $grid->number();
//             $grid->column('banner','轮播图')->display(function($banner){
//                 $banner = ImageUrl($banner,'minio');
//                 if ($this->banner_type == 1){
//                     return "<img src='{$banner}' style='width: 200px;height: 100px;'/>";
//                 }else{
//                     $fileName = basename($banner);
//                     return "<a href='{$banner}' target='_blank' style='color: #1890ff;text-decoration: underline;'>点击播放视频: {$fileName}</a>";
//                 }
//             });
            
            $grid->column('banner')->image(env('APP_URL').'/uploads/',50,50);
            
            $grid->column('status','状态')->using([0=>'下架',1=>'上架'])->badge();


            $grid->disableCreateButton(false);
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit(false);
                $actions->disableQuickEdit();
                $actions->disableDelete(false);
            });

        });
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Banner(), function (Form $form) {

            $form->radio('banner_type','类型')->options([1=>'图片',2=>'视频'])->when(1,function (Form $form){
                $form->image('banner1','封面')
                    ->autoUpload()
                    ->uniqueName()
                    ->accept('jpg,png,gif,jpeg')
                    ->removable(false)
                    ->customFormat(function () {
                        return $this->banner;
                    });
            })->when(2,function (Form $form){
                $form->file('banner2','视频')
                    ->autoUpload()
                    ->uniqueName()
                    ->accept('mp4')
                    ->removable(false)
                    ->customFormat(function () {
                        return $this->banner;
                    });
            });
            $form->hidden('banner');

            $form->radio('status')->options([0=>'下架',1=>'上架'])->default(1);

            // 监听表单保存事件
            $form->saving(function (Form $form) {
                $form->banner = $form->banner_type == 1 ? $form->banner1 : $form->banner2;
                $form->deleteInput('banner1');
                $form->deleteInput('banner2');
            });
        });
    }
}
