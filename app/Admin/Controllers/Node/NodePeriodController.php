<?php

namespace App\Admin\Controllers\Node;

use App\Models\NodePeriod;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use App\Models\RankConfig;
use Dcat\Admin\Http\JsonResponse;
use App\Admin\Actions\Grid\AddNodeOrder;
use App\Models\Base\Language;

class NodePeriodController extends AdminController
{
    public $statusArr = [0=>'下架',1=>'上架'];
    public function __construct() {
//         $rankArr = RankConfig::query()->orderBy('lv', 'asc')->pluck('name', 'lv')->toArray();
//         $this->rankArr = array_merge([0=>''], $rankArr);
    }
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
        return Grid::make(NodePeriod::with(['config']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('period', '抢购轮数');
            $grid->column('status', '上架状态')->using($this->statusArr)->label('success');
            $grid->column('price', '单份金额');
            $grid->column('total_quota', '招募额度');
            $grid->column('over_quota', '已售额度');
            $grid->column('btime', '已售额度');
            $grid->column('etime', '已售额度');
            $grid->column('btime', '开始时间');
            $grid->column('etime', '截止时间');
            $grid->column('created_at');
            
            $grid->disableRowSelector();
            //             $grid->disableEditButton();
                        $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->scrollbarX();    			//滚动条
            $grid->paginate(10);				//分页
            $grid->model()->orderBy('id','desc');
            
            // $grid->setActionClass(Grid\Displayers\Actions::class); // 行操作按钮显示方式 图标方式
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete(); //  禁用删除
                $actions->disableQuickEdit(); //禁用快速修改(弹窗形式)
                $actions->disableView(); //  禁用查看
            });
            $grid->filter(function (Grid\Filter $filter) {
//                 $filter->equal('lv')->select($this->lvArr);
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
        return Form::make(new NodePeriod(), function (Form $form) {
            $form->display('id');
            $form->display('period', '抢购轮数');
//             $form->radio('status', '上架状态')->options($this->statusArr)->required()->default(0);
            $form->number('price', '单份金额')->default(1)->min(1)->required();
            $form->number('total_quota', '招募额度')->min(1)->default(1)->required()->help('实际库存可购买的数量');
            $form->datetime('btime', '开始时间')->required();
            $form->datetime('etime', '截止时间')->required();
            $form->saving(function (Form $form)
            {
                $id = $form->getKey();
                
                $price = @bcadd($form->price, '0', 2);
                if (bccomp($price, '0', 2)<=0) {
                    return $form->response()->error('节点价格不正确');
                }
                $total_quota = @bcadd($form->total_quota, '0', 2);
                if (bccomp($total_quota, '0', 2)<=0 || bccomp($total_quota, $price, 2)<=0) {
                    return $form->response()->error('招募额度不正确');
                }
                
                $form->price = $price;
                $form->total_quota = $total_quota;
                
                $period = intval($form->period);
//                 if ($form->isCreating()) {
//                     // 也可以这样获取自增ID
//                     $res = NodePeriod::query()->where('period', $period)->first();
//                     if ($res) {
//                         return $form->response()->error('轮数已存在');
//                     }
//                 }
//                 if ($form->isEditing()) {
//                     $res = NodePeriod::query()->where('period', $period)->first();
//                     if ($res) {
//                         if ($res->id!=$id){
//                             return $form->response()->error('轮数已存在');
//                         }
//                     }
//                 }
            });
            
//             $form->saved(function (Form $form, $result) {
//                 NodePeriod::SetListCache();
//             });
            
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
        return JsonResponse::make()->success('删除成功')->location('node_period');
    }
}
