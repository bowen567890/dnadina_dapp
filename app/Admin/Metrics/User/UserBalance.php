<?php

namespace App\Admin\Metrics\User;

use App\Admin\Metrics\Level\Request;
use Dcat\Admin\Widgets\Metrics\Card;
use App\Models\User;

class UserBalance extends Card
{
    protected $labels = [1=>'USDT余额',2=>'DHT余额'];
    
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('全网用户余额');
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
                $usdtBalance = User::query()->sum('usdt');
                $dhtBalance = User::query()->sum('dht');
                $this->withContent($usdtBalance, $dhtBalance);
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
    protected function withContent($usdtBalance, $dhtBalance)
    {
        return $this->content(
            <<<HTML
<div class="row" style="margin: 0 18px;">
    <div class="col-12" style="padding: 0 5px;">
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle text-primary mr-1" style="font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[1]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$usdtBalance}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#FFD700; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[2]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$dhtBalance}</span>
        </div>

    </div>
</div>
HTML
        );
    }
}
