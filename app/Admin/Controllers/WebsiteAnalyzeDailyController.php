<?php

namespace App\Admin\Controllers;

use App\Models\WebsiteAnalyzeDaily;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;

class WebsiteAnalyzeDailyController extends AdminController
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

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new WebsiteAnalyzeDaily(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('date');
            $grid->column('register_num');
            
            $grid->column('recharge', '入金统计')->display(function ()
            {
                $html = "";
                $html .= "<div class='margin-top-xs'>累计入金(USDT)：" .$this->recharge_usdt . "</div>";
                $html .= "<div class='margin-top-xs'>节点入金(USDT)：" .$this->recharge_usdt_node . "</div>";
                $html .= "<div class='margin-top-xs'>合约入金(USDT)：" .$this->recharge_usdt_contract . "</div>";
                return $html;
            });
            
            $grid->column('withdrawUsdt', 'USDT提币统计')->display(function ()
            {
                $html = "";
                $html .= "<div class='margin-top-xs'>提币申请：" .$this->withdraw_usdt . "</div>";
                $html .= "<div class='margin-top-xs'>提币放款：" .$this->withdraw_usdt_issue . "</div>";
                return $html;
            });
            
            $grid->column('withdrawBtc', 'BTC提币统计')->display(function ()
            {
                $html = "";
                $html .= "<div class='margin-top-xs'>提币申请：" .$this->withdraw_btc . "</div>";
                $html .= "<div class='margin-top-xs'>提币放款：" .$this->withdraw_btc_issue . "</div>";
                return $html;
            });
            
            $grid->column('withdrawRwa', 'RWA提币统计')->display(function ()
            {
                $html = "";
                $html .= "<div class='margin-top-xs'>提币申请：" .$this->withdraw_rwa . "</div>";
                $html .= "<div class='margin-top-xs'>提币放款：" .$this->withdraw_rwa_issue . "</div>";
                return $html;
            });
          
            $grid->column('income', '收益统计')->display(function ()
            {
                $html = "";
                $html .= "<div class='margin-top-xs'>累计收益(USDT)：" .$this->income_usdt . "</div>";
                $html .= "<div class='margin-top-xs'>累计收益(BTC)：" .$this->income_btc . "</div>";
                $html .= "<div class='margin-top-xs'>累计收益(RWA)：" .$this->income_rwa . "</div>";
                return $html;
            });
            
            $grid->model()->orderBy('id','desc');
            
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableDeleteButton();
            $grid->disableActions();
            $grid->scrollbarX();    			//滚动条
            $grid->paginate(10);				//分页
            
            
            
            $titles = [
                'id' => 'ID',
                'date' => '统计日期',
                'register_num' => '注册人数',
                'recharge_usdt' => '累计入金(USDT)',
                'recharge_usdt_node' => '节点入金(USDT)',
                'recharge_usdt_contract' => '合约入金(USDT)',
                'withdraw_usdt' => '提币申请(USDT)',
                'withdraw_usdt_issue' => '提币放款(USDT)',
                'withdraw_btc' => '提币申请(BTC)',
                'withdraw_btc_issue' => '提币放款(BTC)',
                'withdraw_rwa' => '提币申请(RWA)',
                'withdraw_rwa_issue' => '提币放款(RWA)',
                'income_usdt' => '累计收益(USDT)',
                'income_btc' => '累计收益(BTC)',
                'income_rwa' => '累计收益(RWA)',
            ];
            
            $grid->export($titles)->rows(function ($rows)
            {
                set_time_limit(0);
                ini_set('memory_limit','1024M');
                
//                 foreach ($rows as $index => &$row)
//                 {
//                     $row['rank'] = $rankArr[$row['rank']];
//                     $row['node_rank'] = $nodeArr[$row['node_rank']];
//                     $row['can_withdraw'] = $withdrawArr[$row['can_withdraw']];
//                 }
                return $rows;
            });
            
            
            // $grid->setActionClass(Grid\Displayers\Actions::class); // 行操作按钮显示方式 图标方式
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                // $actions->disableDelete(); //  禁用删除
                // $actions->disableEdit();   //  禁用修改
                // $actions->disableQuickEdit(); //禁用快速修改(弹窗形式)
                // $actions->disableView(); //  禁用查看
            });
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('date')->date();
            });
        });
    }
}
