<?php
namespace App\Admin\Controllers\Cms;

use App\Models\Common\Notice;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use App\Models\Base\Language;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;


class NoticeController extends AdminController
{

    public function index(Content $content)
    {
        return $content
            ->header('公告')
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
        return Grid::make(new Notice(), function (Grid $grid) {
            $grid->column('id');
            $grid->column('name');
            $grid->column('order')->help('fix:降序（从小到大）');
            //$grid->column('order','排序')->label()->sortable();
            $grid->column('status_maps','状态开关')
                  ->switchGroup([
                    'status' => '是否开启',
                    'ispop'  => '是否弹窗',
                  ],true);

            $grid->model()->orderBy('order','asc');
            $grid->model()->orderBy('id','desc');
            
//             $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableViewButton();
//             $grid->disableDeleteButton();
//             $grid->disableActions();
            $grid->scrollbarX();    			//滚动条
            $grid->paginate(10);				//分页

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Notice(), function (Form $form) {

            if ($form->isCreating()){
                $form->action('cms-notice');
            }
           $form->tab('基础', function (Form $form) {
                $form->text('name')->required();
                $form->number('order')->required()->default(99)->help('越小排越前面')->min(1);
                $form->switch('status', '状态')->default(0);
                $form->switch('ispop', '是否弹窗')->default(1);
            })->tab('标题/内容', function (Form $form) {
                  $language = Language::query()->get();
                   $form->embeds('title', '标题', function (Form\EmbeddedForm $form) use ($language) {
                    foreach ($language as $lang) {
                        $lang->show ? $form->text($lang->slug, $lang->name)->required() : $form->hidden($lang->slug, $lang->name);
                    }
                });
                $form->embeds('content', '内容', function (Form\EmbeddedForm $form) use ($language) {
                    foreach ($language as $lang) {
                        $lang->show ? $form->editor($lang->slug, $lang->name)->required() : $form->hidden($lang->slug, $lang->name);
                    }
                });
            });

            $form->saved(function (Form $form) {
                return $form->response()->success('保存成功')->redirect('cms-notice');
            });
//             $form->disableListButton();
            $form->disableDeleteButton();
            $form->disableViewButton();
            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();
            $form->disableResetButton();
        });
    }
}
