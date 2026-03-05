<?php

namespace App\Admin\Metrics\Level;

use Dcat\Admin\Admin;
use Illuminate\Support\Carbon;
use Dcat\Admin\Widgets\Metrics\Card;
use App\Models\User;

class Level extends Card
{
    protected $labels = [0=>'V0',1=>'V1',2=>'V2',3=>'V3',4=>'V4',5=>'V5',6=>'V6',7=>'V7',8=>'V8',9=>'V9',10=>'V10'];

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('团队等级');
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
                $vip0 = User::query()->where('rank',0)->count();
                $vip1 = User::query()->where('rank',1)->count();
                $vip2 = User::query()->where('rank',2)->count();
                $vip3 = User::query()->where('rank',3)->count();
                $vip4 = User::query()->where('rank',4)->count();
                $vip5 = User::query()->where('rank',5)->count();
                $vip6 = User::query()->where('rank',6)->count();
//                 $vip7 = User::query()->where('rank',7)->count();
//                 $vip8 = User::query()->where('rank',8)->count();
//                 $vip9 = User::query()->where('rank',9)->count();
//                 $vip10 = User::query()->where('rank',10)->count();
                $this->withContent($vip0,$vip1,$vip2,$vip3,$vip4,$vip5,$vip6);
        }

    }

    /**
     * 设置卡片头部内容.
     *
     * @param mixed $vip1
     * @param mixed $vip2
     * @param mixed $vip3
     * @param mixed $vip4
     * @param mixed $vip5
     * @param mixed $vip6
     * @param mixed $vip7
     *
     * @return $this
     */
    protected function withContent($vip0,$vip1,$vip2,$vip3,$vip4,$vip5,$vip6)
    {
        return $this->content(
            <<<HTML
<div class="row" style="margin: 0 18px;">
    <div class="col-6" style="padding: 0 5px;">

         <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle text-primary mr-1" style="font-size: 7px;"></i>
                <span style="font-size: 12px;">{$this->labels[0]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$vip0}人</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle text-primary mr-1" style="font-size: 7px;"></i>
                <span style="font-size: 12px;">{$this->labels[1]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$vip1}人</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#FFD700; font-size: 7px;"></i>
                <span style="font-size: 12px;">{$this->labels[2]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$vip2}人</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color: #CD9B9B; font-size: 7px;"></i>
                <span style="font-size: 12px;">{$this->labels[3]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$vip3}人</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#CD6090; font-size: 7px;"></i>
                <span style="font-size: 12px;">{$this->labels[4]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$vip4}人</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#32CD32; font-size: 7px;"></i>
                <span style="font-size: 12px;">{$this->labels[5]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$vip5}人</span>
        </div>
    </div>

    <div class="col-6" style="padding: 0 5px;">
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center">
                <i class="fa fa-circle mr-1" style="color:#FF6347; font-size: 7px;"></i>
                <span style="font-size: 12px;">{$this->labels[6]}</span>
            </div>
            <span class="font-weight-bold" style="font-size: 12px;">{$vip6}人</span>
        </div>

    </div>
</div>
HTML
        );
    }
}
