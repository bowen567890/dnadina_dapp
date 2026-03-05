<?php

namespace App\Admin\Metrics\Recharge;

use App\Admin\Metrics\Level\Request;
use Dcat\Admin\Widgets\Metrics\Card;
use App\Models\User;
use App\Models\NodeOrder;
use App\Models\ProductsOrder;
use App\Models\WebsiteStatistic;
use App\Models\WebsiteAnalyzeDaily;

class RechargeStatistics extends Card
{
    protected $labels = [1=>'昨日入金',2=>'昨日节点入金',4=>'累计入金',5=>'累计节点入金'];
    
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();
        $this->title('充值统计');
    }


    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle( $request)
    {
        
        switch ($request->get('option')) {
            default:
                
                $time = time();
                $cWebsiteAnalyzeDaily = WebsiteAnalyzeDaily::query()->where('date', date('Y-m-d', $time))->first();
                $yWebsiteAnalyzeDaily = WebsiteAnalyzeDaily::query()->where('date', date('Y-m-d', $time-86400))->first();
                
                $y_recharge_usdt = $yWebsiteAnalyzeDaily->recharge_usdt;
                $y_recharge_usdt_node = $yWebsiteAnalyzeDaily->recharge_usdt_node;
                
                $WebsiteStatistic = WebsiteStatistic::query()->where('id', 1)->first();
                $recharge_usdt = $WebsiteStatistic->recharge_usdt;
                $recharge_usdt_node = $WebsiteStatistic->recharge_usdt_node;
                
                $this->withContent($y_recharge_usdt, $y_recharge_usdt_node, $recharge_usdt, $recharge_usdt_node);
        }

    }

    /**
     * 设置卡片头部内容.
     *
     * @param mixed $vip1
     * @param mixed $vip2
     * @param mixed $vip3
     *
     * @return $this
     */
    protected function withContent($y_recharge_usdt, $y_recharge_usdt_node, $recharge_usdt, $recharge_usdt_node)
    {
        return $this->content(
            <<<HTML
<div class="row" style="margin: 0 18px;">
    <div class="col-12" style="padding: 0 5px;">
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle text-primary mr-1" style="font-size: 12px;"></i>
                <span style="font-size: 12px;">{$this->labels[1]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$y_recharge_usdt}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#FFD700; font-size: 12px;"></i>
                <span style="font-size: 12px;">{$this->labels[2]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$y_recharge_usdt_node}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#FFD700; font-size: 12px;"></i>
                <span style="font-size: 12px;">{$this->labels[4]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$recharge_usdt}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#FFD700; font-size: 12px;"></i>
                <span style="font-size: 12px;">{$this->labels[5]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$recharge_usdt_node}</span>
        </div>

    </div>
</div>
HTML
        );
    }
}
