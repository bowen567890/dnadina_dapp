<?php

namespace App\Admin\Metrics\Income;

use App\Admin\Metrics\Level\Request;
use Dcat\Admin\Widgets\Metrics\Card;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\WebsiteStatistic;
use App\Models\WebsiteAnalyzeDaily;

class IncomeStatistics extends Card
{
    protected $labels = [1=>'昨日收益(USDT)',2=>'累计收益(USDT)',3=>'昨日收益(BTC)',4=>'累计收益(BTC)'];
    
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('收益统计');
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
                
                $c_income_usdt = $cWebsiteAnalyzeDaily->income_usdt;
                $c_income_btc = $cWebsiteAnalyzeDaily->income_btc;
                
                
                $y_income_usdt = $yWebsiteAnalyzeDaily->income_usdt;
                $y_income_btc = $yWebsiteAnalyzeDaily->income_btc;
                
                $WebsiteStatistic = WebsiteStatistic::query()->where('id', 1)->first();
                $income_usdt = $WebsiteStatistic->income_usdt;
                $income_btc = $WebsiteStatistic->income_btc;
                
                $this->withContent($y_income_usdt, $income_usdt, $y_income_btc, $income_btc);
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
    protected function withContent($y_income_usdt, $income_usdt, $y_income_btc, $income_btc)
    {
        return $this->content(
            <<<HTML
<div class="row" style="margin: 0 18px;">
    <div class="col-12" style="padding: 0 5px;">
            
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #FFD700; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[1]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$y_income_usdt}</span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #CD9B9B; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[2]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$income_usdt}</span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #CD9B9B; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[3]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$y_income_btc}</span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #CD9B9B; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[4]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$income_btc}</span>
        </div>
    </div>
</div>
HTML
        );
    }
}
