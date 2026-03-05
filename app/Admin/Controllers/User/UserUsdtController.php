<?php

namespace App\Admin\Controllers\User;

use App\Models\UserUsdt;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;

class UserUsdtController extends AdminController
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

    public $cateArr = [
        1=>'系统增加',
        2=>'系统扣除',
        3=>'提币扣除',
        4=>'提币驳回',
        5=>'直推节点',
        6=>'间推节点',
    ];
    
    protected function grid()
    {
        return Grid::make(UserUsdt::with(['user']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('user_id');
            $grid->column('user.address', '用户地址');
            $grid->column('from_user_id');
            $grid->column('type')
            ->display(function () {
                $arr = [1=>'收入', 2=>'支出'];
                $msg = $arr[$this->type];
                $colour = $this->type == 1 ? '#21b978' : '#ea5455';
                return "<span class='label' style='background:{$colour}'>{$msg}</span>";
            });
            $grid->column('cate')->using($this->cateArr)->label();
            $grid->column('total');
//             $grid->column('msg');
            $grid->column('content');
//             $grid->column('ordernum');
            $grid->column('created_at');
//             $grid->column('updated_at')->sortable();
            
            $grid->model()->orderBy('id','desc');
            
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableDeleteButton();
            $grid->disableActions();
            $grid->scrollbarX();    			//滚动条
            $grid->paginate(10);				//分页
            
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('user_id');
                $filter->equal('user.address', '用户地址');
                $filter->equal('type')->select([1=>'收入', 2=>'支出']);
                $filter->equal('cate')->select($this->cateArr);
                // 月份过滤器：只有当输入了月份值时才应用
                // 月份值会通过 request()->input('month') 传递给 UserMinePowerMonth::getTable()
                // 如果没有输入月份，getTable() 会使用主表，可以查询所有数据
//                 $filter->where('month', function ($query) {
//                     // 这里不需要额外的查询逻辑，因为表名已经根据月份动态选择了
//                     // 只需要确保月份参数被传递到 request 中即可
//                 }, '月份')->month();
            });
        });
    }
}
