<?php

namespace App\Admin\Metrics\User;

use App\Admin\Metrics\Level\Request;
use App\Models\UsersPower;
use App\Models\UsersPowerLog;
use Dcat\Admin\Admin;
use Illuminate\Support\Carbon;
use Dcat\Admin\Widgets\Metrics\Card;
use App\Models\User;

class UserPower extends Card
{
    protected $labels = ['累计算力', '有效算力','失效算力','设备算力','销毁算力','节点算力'];

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('算力');
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
                $vip1 = UsersPower::query()->sum('total_power');
                $vip2 = UsersPower::query()->sum('valid_power');
                $vip3 = UsersPower::query()->sum('expired_power');
                $vip4 = UsersPower::query()->sum('equipment_power');
                $vip5 = UsersPower::query()->sum('destroy_power');
                $vip6 = UsersPower::query()->sum('node_power');
                $this->withContent($vip1,$vip2,$vip3,$vip4,$vip5,$vip6);
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
    protected function withContent($vip1, $vip2, $vip3,$vip4,$vip5,$vip6)
    {
        return $this->content(
            <<<HTML
<div class="row" style="margin: 0 18px;">
    <div class="col-6" style="padding: 0 5px;">
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle text-primary mr-1" style="font-size: 8px;"></i>
                <span style="font-size: 14px;">{$this->labels[0]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$vip1}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#FFD700; font-size: 14px;"></i>
                <span style="font-size: 14px;color: red">{$this->labels[1]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;color: red">{$vip2}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #CD9B9B; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[2]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$vip3}</span>
        </div>
    </div>
    <div class="col-6" style="padding: 0 5px;">
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle text-primary mr-1" style="font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[3]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$vip4}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#FFD700; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[4]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$vip5}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #CD9B9B; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[5]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$vip6}</span>
        </div>
    </div>
</div>
HTML
        );
    }
}
