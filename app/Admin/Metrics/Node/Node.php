<?php

namespace App\Admin\Metrics\Node;

use App\Admin\Metrics\Level\Request;
use Dcat\Admin\Widgets\Metrics\Card;
use App\Models\User;

class Node extends Card
{
    protected $labels = [0=>'',1=>'启航节点',2=>'飞跃节点',3=>'巅峰节点'];

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('节点');
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
                $vip1 = User::query()->where('node_rank',1)->count();
                $vip2 = User::query()->where('node_rank',2)->count();
                $vip3 = User::query()->where('node_rank',3)->count();
                $this->withContent($vip1,$vip2,$vip3);
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
    protected function withContent($vip1, $vip2, $vip3)
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
            <span class="font-weight-bold" style="font-size: 14px;">{$vip1}人</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#FFD700; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[2]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$vip2}人</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #CD9B9B; font-size: 14px;"></i>
                <span style="font-size: 14px;">{$this->labels[3]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 14px;">{$vip3}人</span>
        </div>
    </div>
</div>
HTML
        );
    }
}
