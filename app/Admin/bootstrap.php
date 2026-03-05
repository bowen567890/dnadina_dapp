<?php

use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Filter;
use Dcat\Admin\Show;



// use Dcat\Admin\Grid;
// use Dcat\Admin\Grid\Filter;
// use App\Admin\Actions\HiddenContextMenuActions As Actions;

Admin::favicon('/favicon.ico'); //设置网址favicon.ico


Grid::resolving(function (Grid $grid) {
    $grid->paginate(10);
    $grid->addTableClass('fs-12');
    $grid->disableRowSelector();
//     $grid->disableViewButton();
//     $grid->setActionClass(Actions::class);

//     $grid->filter(function (Filter $filter) {
//         $filter->panel();
//     });

//     $grid->showQuickEditButton();
//     $grid->enableDialogCreate();
    $grid->disableBatchDelete();
//     $grid->model()->orderByDesc('id');

    // $grid->disableRowSelector();
//     $grid->withBorder();
    // $grid->export();

//     $grid->actions(function (Actions $actions) {
//         $actions->disableView();
//         $actions->disableDelete();
//         $actions->disableEdit();
//     });
//     $grid->option("dialog_form_area", ["70%", "80%"]);
});

// \Dcat\Admin\Form::resolving(function (\Dcat\Admin\Form $form) {
//     $form->disableEditingCheck();
//     $form->disableCreatingCheck();
//     $form->disableViewCheck();

//     $form->tools(function (\Dcat\Admin\Form\Tools $tools) {
//         $tools->disableDelete();
//         $tools->disableView();
//         // $tools->disableEdit();
//     });
// });


// Filter::resolving(function (Filter $filter) {
//     //控制搜索
//     $filter->panel();
//     //$filter->expand();
//     //$filter->expand(false);
// });

Dcat\Admin\Grid\Column::extend('user',\App\Admin\Extensions\Grid\User::class);
// Dcat\Admin\Grid\Column::extend('percent',\App\Admin\Extensions\Grid\Percent::class);
// Dcat\Admin\Grid\Column::extend('status',\App\Admin\Extensions\Grid\Status::class);
Dcat\Admin\Grid\Column::extend('baseUser',\App\Admin\Extensions\Grid\BaseUser::class);
Dcat\Admin\Grid\Column::extend('loanDetails',\App\Admin\Extensions\Grid\LoanDetails::class);


Admin::css([
    "https://unpkg.com/element-ui/lib/theme-chalk/index.css",
    "css/admin.css",
    "js/go.js",
//     "css/menu-fix-pure.css" // 纯CSS菜单修复方案（推荐，避免JS冲突）
]);

// 添加jQuery Form插件修复
// Admin::js([
//     'vendor/dcat-admin/dcat/js/jquery-form-fix.js'
// ]);
