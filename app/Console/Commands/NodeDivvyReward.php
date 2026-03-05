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
use App\Models\SignOrder;
use App\Models\UserUsdt;
use App\Models\PowerEvent;
use App\Models\FundPoolLog;
use App\Models\FundPool;
use App\Models\UserDht;


class NodeDivvyReward extends Command
{
    // 自定义脚本命令签名
    protected $signature = 'command:NodeDivvyReward';

    // 自定义脚本命令描述
    protected $description = '每日节点加权奖励';


    // 创建一个新的命令实例
    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle()
    {
        $lockKey = 'command:NodeDivvyReward';
        $MyRedis = new MyRedis();
//                             $MyRedis->del_lock($lockKey);
        $lock = $MyRedis->setnx_lock($lockKey, 600);
        if ($lock)
        {
            $time = time();
            $date = date('Y-m-d', $time);
            $datetime = date('Y-m-d H:i:s', $time);
            
            $subAmount = $totalDht = '0';
            $FundPool = FundPool::query()->where('type', 2)->first();
            $dhtPrice = MainCurrency::query()->where('id', 3)->value('rate');
            
            $dhtData = $userList = [];
            
            if ($FundPool && $FundPool->amount>0 && bccomp($dhtPrice, '0', 10)>0) 
            {
                $totalDht = bcdiv($FundPool->amount, $dhtPrice, 6);
                if (bccomp($totalDht, '0', 6)>0) 
                {
                    $ordernum = get_ordernum();
                    $subAmount = $FundPool->amount;
                    
                    $totalYeji = '0';
                    $list = User::query()
                        ->where('self_node', '>', 0)
                        ->get(['id','address','self_node'])
                        ->toArray();
                    foreach ($list as $val) {
                        $totalYeji = bcadd($totalYeji, $val['self_node'], 2);
                    }
                    
                    foreach ($list as $val) 
                    {
                        $rate = bcdiv($val['self_node'], $totalYeji, 6);
                        $num = bcmul($totalDht, $rate, 6);
                        
                        if (bccomp($num, '0', 6)>0) 
                        {
                            $userList[$val['id']] = [
                                'user_id' => $val['id'],
                                'num' => $num
                            ];
                            //分类1系统增加2系统扣除3提币扣除4提币驳回5锁仓释放6节点加权
                            $dhtData[] = [
                                'ordernum' => $ordernum,
                                'user_id' => $val['id'],
                                'from_user_id' => 0,
                                'type' => 1,
                                'cate' => 6,
                                'total' => $num,
                                'msg' => '节点加权',
                                'content' => "节点加权",
                                'created_at' => $datetime,
                                'updated_at' => $datetime,
                            ];
                        }
                    }
                }
            }
            
            FundPool::query()->where('type', 2)->decrement('amount', $subAmount);
            
            $FundPoolLog = new FundPoolLog();
            $FundPoolLog->date = $date;
            $FundPoolLog->type = 2;
            $FundPoolLog->usdt = $subAmount;
            $FundPoolLog->dht = $totalDht;
            $FundPoolLog->dht_price = $dhtPrice;
            $FundPoolLog->save();
            
            if ($userList) {
                foreach ($userList as $uval) {
                    User::query()->where('id', $uval['user_id'])->increment('dht', $uval['num']);
                }
            }
            
            if ($dhtData) {
                $dhtData = array_chunk($dhtData, 1000);
                foreach ($dhtData as $ndata) {
                    UserDht::query()->insert($ndata);
                }
            }
            
            /******** 自动买币到底池 ********/
            
            $MyRedis = new MyRedis();
            $MyRedis->del_lock($lockKey);
        }
    }
}
