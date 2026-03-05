<?php

namespace App\Admin\Controllers\Cms;


use App\Enums\ArticleType;
use App\Models\Common\News;
use App\Models\Base\Language;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Str;
use App\Admin\Renderable\News\Preview;
use Dcat\Admin\Grid\Displayers\DropdownActions As Actions;


class NewsController extends AdminController
{

    protected $title = "新闻/资讯";

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new News(), function (Grid $grid) {
            $grid->column('id','No.')
               ->display(function($value){ return 'No.'.$value;})
               ->bold();

            $grid->column('cover','封面')->image('', 50, 30);
            $grid->column('name','名称');
            $grid->column('Preview', '新闻')->display('预览')->modal(Preview::make());
           // $grid->column('fake_read_nums','显示阅读数')->emp()->bold();
           // $grid->column('read_nums','真实阅读数')->emp()->bold();
            $grid->column('read_min','阅读分钟')->emp()->bold();
            $grid->column('pushd_at','显示的发布时间')->sortable()->help('fix:前端以发布时间排序');
         //   $grid->column('order','排序'->sortable()->editable(true);
            $grid->column('created_at')->sortable();
            $grid->createMode('default');

            $grid->column('status_maps','状态开关')
                  ->switchGroup([
                    'status' => '是否上架',
                    'is_top' => '是否置顶',
                  ],true);

            $grid->disableCreateButton(false);  $grid->actions(function (Actions $actions) {
          
                $actions->disableEdit(false);
                $actions->disableQuickEdit();
                $actions->disableDelete(false);
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('name','名称')->width(2);
                $filter->like('title','标题')->width(2);
                $filter->between('pushd_at', '发布时间')->datetime()->width(4);
                $filter->between('created_at', '创建时间')->datetime()->width(4);
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
        return Form::make(new News(), function (Form $form) {

           $form->tab('基础', function (Form $form) {
                $form->text('name','中文标题')->help("由于多语言，方便管理人员识别");
                $form->image('cover','封面')
                    ->autoUpload()
                    ->uniqueName()
                    ->required()
                    ->accept('jpg,png,gif,jpeg')
                    ->removable(false);

               $form->number('fake_read_nums','显示阅读数')->default(99)->required();
                $form->datetime('pushd_at','发布时间')->required();
                $form->number('read_min','阅读分钟')->required();

                $form->switch('status')->default(1)->required();
                $form->switch('is_top','是否置顶')->default(0);


            })->tab('标题/描述/内容', function (Form $form) {

                $language = Language::query()->get();
                $form->embeds('title', '标题', function (Form\EmbeddedForm $form) use ($language) {
                    foreach ($language as $lang) {
                        $lang->show ? $form->text($lang->slug, $lang->name)->required() : $form->hidden($lang->slug, $lang->name);
                    }
                });

                $form->embeds('describe', '简介', function (Form\EmbeddedForm $form) use ($language) {
                    foreach ($language as $lang) {
                        $lang->show ? $form->textarea($lang->slug, $lang->name)->required() : $form->hidden($lang->slug, $lang->name);
                    }
                });


                $form->embeds('content', '内容', function (Form\EmbeddedForm $form) use ($language) {
                    foreach ($language as $lang) {
                        $lang->show ? $form->editor($lang->slug, $lang->name)->required() : $form->hidden($lang->slug, $lang->name);
                    }
                });

             //   $form->editor('content','内容')->required();
            });
        });
    }
}
