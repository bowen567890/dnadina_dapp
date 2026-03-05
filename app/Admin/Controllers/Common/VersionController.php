<?php
namespace App\Admin\Controllers\Common;


use App\Models\Common\Version;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class VersionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '版本管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Version());
        $grid->model()->orderBy('id', 'desc');
        $grid->column('title','版本名字');
        $grid->column('version_code','版本号');
        $grid->column('hot_version_code','热更新版本号');

        $grid->column('remark', '版本说明');

        $grid->column('platform', '平台')->display(function ($p) {
            $name = '';
            switch ($p){
                case 'I':
                    $name = 'ios';
                    break;
                case 'A':
                    $name = 'Android';
                    break;
            }
            return $name;
        })->label();
        $grid->column('force', '是否强制更新')->display(function ($p) {
            $name = '';
            switch ($p){
                case 0:
                    $name = '否';
                    break;
                case 1:
                    $name = '是';
                    break;
            }
            return $name;
        });
        $grid->column('package_url','包路径')->link();
        $grid->column('hot_package_url','热更新包路径')->link();
        $grid->column('status','版本状态')->switch();
        $grid->column('created_at', '发布时间');

        $grid->disableCreateButton(false);
        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Version());
        $form->text('title', '版本名字')->required();
        $form->textarea('remark', '版本说明');
        $form->select('platform', '平台')->options([
            'I' => 'ios',
            'A' => 'Android',
        ])->required();
        $form->switch('force', '是否强制更新');
        $form->text('version_code', '版本号')->required();
        $form->text('package_url', '包路径')->required();
        $form->text('hot_version_code', '热更新版本号')->required();
        $form->text('hot_package_url', '热更新包路径')->required();
        $form->switch('status', '版本状态');

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        return $form;
    }
}
