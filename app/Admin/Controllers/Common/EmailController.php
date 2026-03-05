<?php

namespace App\Admin\Controllers\Common;

use App\Models\Common\MailModel;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class EmailController extends AdminController
{

    protected $title = "邮件记录";

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new MailModel(), function (Grid $grid) {

            $grid->model()->orderBy('created_at', 'desc');
            $grid->column('email','邮箱');
            $grid->column('type','类型')->using(['Forget'=>'找回密码','Register'=>'注册'])->label();
            $grid->column('code','验证码')->copyable();
            $grid->combine('邮件', ['email','type' ,'code']);
            $grid->column('ip','IP');
            $grid->column('local','语言')->badge();
            $grid->combine('用户', ['ip','local']);
            $grid->column('created_at','发送时间');
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('email','邮箱')->width(2);
                $filter->equal('type', '类型')->select(['Forget'=>'找回密码','Register'=>'注册'])->width(2);
            });

            $grid->disableActions();
            $grid->disableCreateButton();
        });
    }
}
