<?php

namespace App\Admin\Metrics\Summary;

use App\Models\WebsiteAnalyze;
use Dcat\Admin\Widgets\Metrics\Card;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Dcat\Admin\Widgets\Table;

class A2 extends Card
{

    /**
     * 卡片底部内容.
     *
     * @var string|Renderable|\Closure
     */
    protected $type;

    /**
     * 初始化卡片.
     */
    protected function init()
    {
        parent::init();
        $this->title('概况<span style="font-size:5px;">（总）</span>');
        $this->subTitle('全部');
    }
    /**
     * 处理请求.
     *
     * @param Request $request
     *
     * @return void
     */
    public function handle(Request $request)
    {
        switch ($request->get('option')) {
               default:
                $this->_renderContent();
        }
    }


    public function _renderContent()
    {

        $show_point = 2;
        $content = parent::renderContent();

        $todayWebsiteAnalyze = WebsiteAnalyze::query()->where('date', date('Y-m-d'))->firstOrCreate(['date' => date('Y-m-d')]);
        $today = [
            'register_num' => $todayWebsiteAnalyze->register_num,
            'backend_recharge_usdt' => $todayWebsiteAnalyze->backend_recharge_usdt,
            'backend_recharge_coin' => $todayWebsiteAnalyze->backend_recharge_coin,
            'recharge_usdt_num' => $todayWebsiteAnalyze->recharge_usdt_num,
            'recharge_usdt_count' => $todayWebsiteAnalyze->recharge_usdt_count,
            'recharge_coin_num' => $todayWebsiteAnalyze->recharge_coin_num,
            'recharge_coin_count' => $todayWebsiteAnalyze->recharge_coin_count,
            'withdraw_num' => $todayWebsiteAnalyze->withdraw_num,
            'withdraw_count' => $todayWebsiteAnalyze->withdraw_count,
            'withdraw_fee' => $todayWebsiteAnalyze->withdraw_fee,
            'power_income' => $todayWebsiteAnalyze->power_income,
            'node_withdraw_income' => $todayWebsiteAnalyze->node_withdraw_income,
            'circulation_volume' => $todayWebsiteAnalyze->circulation_volume,
            'destroy_volume' => $todayWebsiteAnalyze->destroy_volume,
        ];

        //昨日
        $yesterdayWebsiteAnalyze =  WebsiteAnalyze::query()->firstOrCreate(['date' =>date('Y-m-d',strtotime('-1 day'))]);
        $yesterday = [
            'register_num' => $yesterdayWebsiteAnalyze->register_num,
            'backend_recharge_usdt' => $yesterdayWebsiteAnalyze->backend_recharge_usdt,
            'backend_recharge_coin' => $yesterdayWebsiteAnalyze->backend_recharge_coin,
            'recharge_usdt_num' => $yesterdayWebsiteAnalyze->recharge_usdt_num,
            'recharge_usdt_count' => $yesterdayWebsiteAnalyze->recharge_usdt_count,
            'recharge_coin_num' => $yesterdayWebsiteAnalyze->recharge_coin_num,
            'recharge_coin_count' => $yesterdayWebsiteAnalyze->recharge_coin_count,
            'withdraw_num' => $yesterdayWebsiteAnalyze->withdraw_num,
            'withdraw_count' => $yesterdayWebsiteAnalyze->withdraw_count,
            'withdraw_fee' => $yesterdayWebsiteAnalyze->withdraw_fee,
            'power_income' => $yesterdayWebsiteAnalyze->power_income,
            'node_withdraw_income' => $yesterdayWebsiteAnalyze->node_withdraw_income,
            'circulation_volume' => $todayWebsiteAnalyze->circulation_volume,
            'destroy_volume' => $todayWebsiteAnalyze->destroy_volume,
        ];


        $allSql = WebsiteAnalyze::query();
        $all  = [
            'register_num' => $allSql->sum('register_num'),
            'backend_recharge_usdt' => $allSql->sum('backend_recharge_usdt'),
            'backend_recharge_coin' => $allSql->sum('backend_recharge_coin'),
            'recharge_usdt_num' => $allSql->sum('recharge_usdt_num'),
            'recharge_usdt_count' => $allSql->sum('recharge_usdt_count'),
            'recharge_coin_num' => $allSql->sum('recharge_coin_num'),
            'recharge_coin_count' => $allSql->sum('recharge_coin_count'),
            'withdraw_num' => $allSql->sum('withdraw_num'),
            'withdraw_count' => $allSql->sum('withdraw_count'),
            'withdraw_fee' => $allSql->sum('withdraw_fee'),
            'power_income' => $allSql->sum('power_income'),
            'node_withdraw_income' => $allSql->sum('node_withdraw_income'),
            'circulation_volume' => $allSql->sum('circulation_volume'),
            'destroy_volume' => $allSql->sum('destroy_volume'),
        ];


       $table = Table::make(['日期','注册','后台充值U','后台充值币','用户充值U','用户充值币','用户提现','提现手续费','算力收益','提现分红','流通量','销毁量'],
        [
            [
                '今日',
                '<span style="font-size:15px; font-style: oblique;color:red;">'.$today['register_num'].'</span>人',
                '<span style="font-size:15px; font-style: oblique;color:red;">'.$today['backend_recharge_usdt'].'</span>',
                '<span style="font-size:15px; font-style: oblique;color:red;">'.$today['backend_recharge_coin'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$today['recharge_usdt_num'].'</span>('.$today['recharge_usdt_count'].')',
                '<span style="font-size:15px; font-style: oblique;">'.$today['recharge_coin_num'].'</span>('.$today['recharge_coin_count'].')',
                '<span style="font-size:15px; font-style: oblique;">'.$today['withdraw_num'].'</span>('.$today['withdraw_count'].')',
                '<span style="font-size:15px; font-style: oblique;">'.$today['withdraw_fee'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$today['power_income'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$today['node_withdraw_income'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$today['circulation_volume'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$today['destroy_volume'].'</span>',
            ],
            [
                '昨日',
                '<span style="font-size:15px; font-style: oblique;color:red;">'.$yesterday['register_num'].'</span>人',
                '<span style="font-size:15px; font-style: oblique;color:red;">'.$yesterday['backend_recharge_usdt'].'</span>',
                '<span style="font-size:15px; font-style: oblique;color:red;">'.$yesterday['backend_recharge_coin'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$yesterday['recharge_usdt_num'].'</span>('.$yesterday['recharge_usdt_count'].')',
                '<span style="font-size:15px; font-style: oblique;">'.$yesterday['recharge_coin_num'].'</span>('.$yesterday['recharge_coin_count'].')',
                '<span style="font-size:15px; font-style: oblique;">'.$yesterday['withdraw_num'].'</span>('.$yesterday['withdraw_count'].')',
                '<span style="font-size:15px; font-style: oblique;">'.$yesterday['withdraw_fee'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$yesterday['power_income'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$yesterday['node_withdraw_income'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$yesterday['circulation_volume'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$yesterday['destroy_volume'].'</span>',
            ],
            [
                '全部',
                '<span style="font-size:15px; font-style: oblique;color:red;">'.$all['register_num'].'</span>人',
                '<span style="font-size:15px; font-style: oblique;color:red;">'.$all['backend_recharge_usdt'].'</span>',
                '<span style="font-size:15px; font-style: oblique;color:red;">'.$all['backend_recharge_coin'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$all['recharge_usdt_num'].'</span>('.$all['recharge_usdt_count'].')',
                '<span style="font-size:15px; font-style: oblique;">'.$all['recharge_coin_num'].'</span>('.$all['recharge_coin_count'].')',
                '<span style="font-size:15px; font-style: oblique;">'.$all['withdraw_num'].'</span>('.$all['withdraw_count'].')',
                '<span style="font-size:15px; font-style: oblique;">'.$all['withdraw_fee'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$all['power_income'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$all['node_withdraw_income'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$all['circulation_volume'].'</span>',
                '<span style="font-size:15px; font-style: oblique;">'.$all['destroy_volume'].'</span>',
            ]
        ])->addTableClass(['table-text-center']);

        return  $this->content($table);
    }

}
