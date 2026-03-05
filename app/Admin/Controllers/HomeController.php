<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\Level\Level;
use App\Admin\Metrics\Node\Node;
use App\Admin\Metrics\Node\NodeUserPower;
use App\Admin\Metrics\Summary\A2;
use App\Admin\Metrics\User\TotalUsers;
use App\Admin\Metrics\User\UserCoin;
use App\Admin\Metrics\User\UserBalance;
use App\Admin\Metrics\User\UserLevelPower;
use App\Admin\Metrics\User\UserPower;
use App\Admin\Metrics\Recharge\RechargeStatistics;
use App\Http\Controllers\Controller;
use App\Models\CurrencyExchange;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use App\Admin\Metrics\Withdraw\WithdrawStatisticsUsdt;
use App\Admin\Metrics\Withdraw\WithdrawStatisticsBtc;
use App\Admin\Metrics\Withdraw\WithdrawStatisticsRwa;
use App\Admin\Metrics\Income\IncomeStatistics;

class HomeController extends Controller
{
    public function index(Content $content)
        {
            //今日挖矿（RAB），累计挖矿，
              $content = $content->header('数据概览')->description('掌控一切资源');
              if (Admin::user()->isRole('administrator') || Admin::user()->can('main_data')) {
                  $content->body(function (Row $row) {
                      $row->column(3, new TotalUsers());
                      $row->column(3, new Level());
                      $row->column(3, new Node());
                      $row->column(3, new UserBalance());
                  });
                  
                  $content->body(function (Row $row) {
                      $row->column(3, new RechargeStatistics());
                      $row->column(3, new WithdrawStatisticsUsdt());
                  });
                  
                  $content->body(function (Row $row) {
//                       $row->column(3, new IncomeStatistics());
                  });

//                   // 今日价格模块
//                   $content->body(function (Row $row) {
//                       try {
//                           // 从FameEX API获取实时价格
//                           $apiUrl = 'https://openapi.fameex.com/sapi/v1/ticker?symbol=FACUSDT';
//                           $response = file_get_contents($apiUrl);
//                           $data = json_decode($response, true);
//                           $price = $data['last'] ?? 0;
//                       } catch (Exception $e) {
//                           // 如果API调用失败，使用备用方法
//                           $price = CurrencyExchange::getPrice('FAC', 'USDT');
//                       }
//                       $row->column(12, "<div class='card border-primary'><div class='card-header bg-primary text-white'>实时价格</div><div class='card-body'><h3 class='text-success'>FAC≈USDT: " . number_format($price, 6) . "</h3></div></div>");
//                   });
//                  //其他
//                  $content->body(function (Row $row) {
//                      $row->column(6, new UserPower());
//                      $row->column(6, new UserLevelPower());
//                      $row->column(4,new NodeUserPower());
//                  });
//                  //概况
//                   $content->body(function (Row $row) {
//                       $row->column(12, new A2());
//                   });

              }
            return $content;
        }
}
