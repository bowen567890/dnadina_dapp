<?php

namespace App\Admin\Controllers;


use App\Models\Base\Language;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Str;
use Dcat\Admin\Http\JsonResponse;

class LanguageController extends AdminController
{

    protected $title = "语言设置";

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Language(), function (Grid $grid) {
            $grid->column('id')->sortable();
         //   $grid->column('icon')->image('', 50, 50);
            $grid->column('name');
            $grid->column('value');
            $grid->column('slug');
            $grid->column('color','颜色值')->display(function($color){
                 return '<span class="badge" style="background:'.$color.'">'.$color.'</span>';
            });
            $grid->column('order');
            $grid->column('status', '启用')->switch();
            $grid->column('show', '后台显示')->switch();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('status','启用')->select([1=>'启用',0=>'禁用'])->width(2);
                $filter->equal('show','后台显示')->select([1=>'启用',0=>'禁用'])->width(2);
            });

            $grid->disableCreateButton(false);
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
        return Show::make($id, new Language(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('value');
            $show->field('slug');
            $show->field('order');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Language(), function (Form $form) {

           // $form->image('icon','图标')->autoUpload()->uniqueName();
            $form->text('color','颜色值')->help('该ID值请咨询技术人员填写');
            $form->text('name');
            $form->text('value');
            $form->text('slug');
            $form->text('order');
            $form->switch('status');
            $form->switch('show');

            $form->saving(function (Form $form) {
                if ($form->slug) {
                    $form->slug = Str::upper($form->slug);
                }

            });
            
            $form->saved(function (Form $form, $result) {
                Language::SetListCache();
            });
        });
    }
    
    /**
     * 删除
     */
    public function destroy($id)
    {
        Language::query()->where('id', $id)->delete();
        Language::SetListCache();
        return JsonResponse::make()->success('删除成功')->location('language');
    }
}
