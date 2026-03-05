<?php

namespace App\Admin\Metrics\User;

use App\Admin\Metrics\Level\Request;
use Dcat\Admin\Widgets\Metrics\Card;
use App\Models\User;

class TotalUsers extends Card
{
    protected $labels = ['累计注册', '今日注册'];

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();
        $this->title('注册');
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
                $totalUser = User::query()->count();
                $todayUser = User::query()->whereDate('created_at',date('Y-m-d'))->count();
                $this->withContent($totalUser,$todayUser);
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
    protected function withContent($totalUser,$todayUser)
    {
        return $this->content(
            <<<HTML
<div class="row" style="margin: 0 18px;">
    <div class="col-12" style="padding: 0 5px;">
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle text-primary mr-1" style="font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[0]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$totalUser}人</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#FFD700; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[1]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$todayUser}人</span>
        </div>
    </div>
</div>
HTML
        );
    }
}
