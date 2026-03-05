<?php
namespace App\Admin\Controllers\Cms;

use App\Models\Common\Notice;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use App\Models\Base\Language;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;


class NoticeCopyController extends AdminController
{

    public function index(Content $content)
    {
        return $content
            ->header('公告')
            ->body(function (Row $row) {
                $row->column(4, $this->grid());
                $row->column(8, $this->form());
            });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Notice(), function (Grid $grid) {
            $grid->model()->orderByDesc('order');
            $grid->column('name')->help('fix:降序（从大到小）');
            //$grid->column('order','排序')->label()->sortable();
            $grid->column('status_maps','状态开关')
                  ->switchGroup([
                    'status' => '是否开启',
                    'ispop'  => '是否弹窗',
                  ],true);

            $grid->disableEditButton();
            $grid->disableDeleteButton();


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
                $form->number('order')->required();
                $form->switch('status', '状态')->default(0);
                $form->switch('ispop', '是否弹窗')->default(1);
            })->tab('标题/内容', function (Form $form) {
                  $language = Language::query()->get();
                   $form->embeds('title', '标题', function (Form\EmbeddedForm $form) use ($language) {
                    foreach ($language as $lang) {
                            $lang->show ? $form->text($lang->slug, $lang->name) : $form->hidden($lang->slug, $lang->name);
                    }
                });
                $form->embeds('content', '内容', function (Form\EmbeddedForm $form) use ($language) {
                    foreach ($language as $lang) {
                            $lang->show ? $form->textarea($lang->slug, $lang->name) : $form->hidden($lang->slug, $lang->name);
                    }
                });
            });

            $form->saved(function (Form $form) {
                return $form->response()->success('保存成功')->redirect('cms-notice');
            });
            $form->disableListButton();

        });
    }
}
