<?php
namespace App\Console\Commands;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\MyRedis;
use App\Models\MainCurrency;
use App\Models\RankConfig;
use App\Models\DepthConfig;
use App\Models\LuckyPool;
use App\Models\NodePool;
use App\Models\UserPower;
use App\Models\UserDogbee;
use App\Models\SeeConfig;
use App\Models\SyncPower;
use App\Models\SignOrder;
use App\Models\ManageRankConfig;
use App\Models\UserUsdt;
use App\Models\UserRankingMonth;
use App\Models\UserRankingDay;
use App\Models\TicketOrder;
use App\Models\PowerEvent;
use App\Models\ProductsStatistic;
use App\Models\WebsiteAnalyzeDaily;
use App\Models\FundPoolLog;


class CreateTomorrowData extends Command
{
    // 自定义脚本命令签名
    protected $signature = 'command:CreateTomorrowData';

    // 自定义脚本命令描述
    protected $description = '创建明日数据统计';


    // 创建一个新的命令实例
    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle()
    {
        $lockKey = 'command:CreateTomorrowData';
        $MyRedis = new MyRedis();
//                             $MyRedis->del_lock($lockKey);
        $lock = $MyRedis->setnx_lock($lockKey, 600);
        if ($lock)
        {
            $time = time();
            $cday = date('Y-m-d', $time);
            $tday = date('Y-m-d', $time+86400);
            
            $res = WebsiteAnalyzeDaily::query()->where('date', $cday)->exists();
            if (!$res) {
                $cWebsiteAnalyzeDaily = new WebsiteAnalyzeDaily();
                $cWebsiteAnalyzeDaily->date = $cday;
                $cWebsiteAnalyzeDaily->save();
            }
            
            $res = WebsiteAnalyzeDaily::query()->where('date', $tday)->exists();
            if (!$res) {
                $tWebsiteAnalyzeDaily = new WebsiteAnalyzeDaily();
                $tWebsiteAnalyzeDaily->date = $tday;
                $tWebsiteAnalyzeDaily->save();
            }
            
            $MyRedis = new MyRedis();
            $MyRedis->del_lock($lockKey);
        }
    }
}
