<?php

namespace App\Admin\Controllers\Node;

use App\Models\NodeConfig;
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

class NodeConfigController extends AdminController
{
    public $lvArr = [0=>'',1=>'启航节点',2=>'飞跃节点',3=>'巅峰节点'];
    public $rankArr = [];
    public function __construct() {
        $rankArr = RankConfig::query()->orderBy('lv', 'asc')->pluck('name', 'lv')->toArray();
        $this->rankArr = array_merge([0=>''], $rankArr);
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
        return Grid::make(new NodeConfig(), function (Grid $grid) {
            
            $grid->tools(function  (Grid\Tools  $tools)  {
//                 $tools->append(new AddNodeOrder());
            });
            
            $grid->column('id')->sortable();
            $grid->column('lv')->using($this->lvArr)->label('success');
//             $grid->column('name');
            $grid->column('image', '图标')->image(env('APP_URL').'/uploads/', 50, 50);
//             $grid->column('sales');
            $grid->column('updated_at')->sortable();
            // $grid->setActionClass(Grid\Displayers\Actions::class); // 行操作按钮显示方式 图标方式
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete(); //  禁用删除
//                 $actions->disableEdit();   //  禁用修改
                $actions->disableQuickEdit(); //禁用快速修改(弹窗形式)
                $actions->disableView(); //  禁用查看
            });
            $grid->disableCreateButton();
        });
    }

    protected function form()
    {
        return Form::make(new NodeConfig(), function (Form $form) {
            $form->display('id');
//             $form->display('lv');
//             $form->display('name');
            $language = Language::query()->get();
            $form->embeds('name', '节点名称', function (Form\EmbeddedForm $form) use ($language) {
                foreach ($language as $lang) {
                    $lang->show ? $form->text($lang->slug, $lang->name)->required() : $form->hidden($lang->slug, $lang->name);
                }
            });
            
//             $form->number('price')->min(1)->default(1)->required();
//             $form->decimal('price')->default(1)->required();
//             $form->select('give_rank')->options($this->rankArr)->default(0);
//             $form->number('stock', '节点库存')->min(0)->default(0)->required()->help('实际库存可购买的数量');
//             $form->number('total_quantity', '招募数量')->min(0)->default(0)->required()->help('仅前端展示,无其他用途');
            $form->image('image', '图标')->uniqueName()->maxSize(10240)->accept('jpg,png,jpeg')->autoUpload()->removable(false);
//             $form->text('sales');
//             $form->text('image');

            $form->saving(function (Form $form)
            {
                $id = $form->getKey();
                
//                 $price = @bcadd($form->price, '0', 2);
//                 if (bccomp($price, '0', 2)<=0) {
//                     return $form->response()->error('节点价格不正确');
//                 }
//                 $form->price = $price;
                
//                 $zhi_num = intval($form->zhi_num);
//                 $zhi_node = intval($form->zhi_node);
//                 if ($zhi_num<=0 || $zhi_node<=0) {
//                     $zhi_num = $zhi_node = 0;
//                 }
                
//                 $form->give_rank = intval($form->give_rank);
            });
            
        
            $form->saved(function (Form $form, $result) {
                NodeConfig::SetListCache();
            });
            
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
        return JsonResponse::make()->success('删除成功')->location('node_config');
    }
}
