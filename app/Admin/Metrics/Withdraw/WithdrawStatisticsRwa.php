<?php

namespace App\Admin\Metrics\Withdraw;

use App\Admin\Metrics\Level\Request;
use Dcat\Admin\Widgets\Metrics\Card;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\WebsiteStatistic;
use App\Models\WebsiteAnalyzeDaily;

class WithdrawStatisticsRwa extends Card
{
    protected $labels = [1=>'今日提币',2=>'今日发放',3=>'昨日提币',4=>'昨日发放', 5=>'累计提币', 6=>'累计发放'];
    
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('RWA提币统计');
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
                
                $c_withdraw = $cWebsiteAnalyzeDaily->withdraw_rwa;
                $c_withdraw_issue = $yWebsiteAnalyzeDaily->withdraw_rwa_issue;
                
                $y_withdraw = $yWebsiteAnalyzeDaily->withdraw_rwa;
                $y_withdraw_issue = $yWebsiteAnalyzeDaily->withdraw_rwa_issue;
                
                $WebsiteStatistic = WebsiteStatistic::query()->where('id', 1)->first();
                $withdraw = $WebsiteStatistic->withdraw_rwa;
                $withdraw_issue = $WebsiteStatistic->withdraw_rwa_issue;
                
                $this->withContent($c_withdraw, $c_withdraw_issue, $y_withdraw, $y_withdraw_issue, $withdraw, $withdraw_issue);
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
    protected function withContent($c_withdraw, $c_withdraw_issue, $y_withdraw, $y_withdraw_issue, $withdraw, $withdraw_issue)
    {
        return $this->content(
            <<<HTML
<div class="row" style="margin: 0 18px;">
    <div class="col-12" style="padding: 0 5px;">
            
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #FFD700; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[3]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$y_withdraw}</span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #CD9B9B; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[4]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$y_withdraw_issue}</span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #CD9B9B; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[5]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$withdraw}</span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #CD9B9B; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[6]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$withdraw_issue}</span>
        </div>
    </div>
</div>
HTML
        );
    }
}
