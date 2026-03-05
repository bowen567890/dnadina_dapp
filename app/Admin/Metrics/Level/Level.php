<?php

namespace App\Admin\Metrics\Level;

use Dcat\Admin\Admin;
use Illuminate\Support\Carbon;
use Dcat\Admin\Widgets\Metrics\Card;
use App\Models\User;

class Level extends Card
{
    protected $labels = [0=>'会员',1=>'联创'];

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
//                 $vip7 = User::query()->where('rank',7)->count();
//                 $vip8 = User::query()->where('rank',8)->count();
//                 $vip9 = User::query()->where('rank',9)->count();
//                 $vip10 = User::query()->where('rank',10)->count();
                $this->withContent($vip0,$vip1);
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
    protected function withContent($vip0,$vip1)
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

        
</div>
HTML
        );
    }
}
