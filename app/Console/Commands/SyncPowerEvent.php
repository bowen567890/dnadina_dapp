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


class SyncPowerEvent extends Command
{
    // 自定义脚本命令签名
    protected $signature = 'command:SyncPowerEvent';

    // 自定义脚本命令描述
    protected $description = '业绩事件';

    protected $userList = [];

    // 创建一个新的命令实例
    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle()
    {
        $lockKey = 'command:SyncPowerEvent';
        $MyRedis = new MyRedis();
//                             $MyRedis->del_lock($lockKey);
        $lock = $MyRedis->setnx_lock($lockKey, 600);
        if ($lock)
        {
            $list = User::query()
                    ->join('power_event as p', 'users.id', '=', 'p.user_id')
                    ->where('p.is_sync', '=', 0)
                    ->orderBy('p.id', 'asc')
                    ->get([
                        'p.id','p.user_id','p.type','p.op_type','p.usdt','p.ordernum','p.is_sync',
                        'users.parent_id','users.deep','users.path','p.created_at'
                    ])
                    ->toArray();
            if ($list)
            {
                $time = time();
                $date = date('Y-m-d H:i:s', $time);
                
                //等级配置
                $rankConf = RankConfig::GetListCache();
                $rankConf = $rankConf ? array_column($rankConf, null, 'lv') : [];
                
                $userModel = new User();
                
                $logIds = [];
                
                foreach ($list as $val) 
                {
                    $logIds[] = $val['id'];
                    
                    //事件类型1节点事件2购买合约3挖矿扣除
                    if ($val['type']==1) 
                    {
                        $userModel->handleSelfNode($val['user_id'], $val['usdt'], $val['op_type']);
                    } 
                    
                    if ($val['parent_id']>0 && $val['path']) 
                    {
                        //团队业绩
                        if ($val['type']==1) 
                        {
                            $userModel->handleTeamNode($val['path'], $val['usdt'], $val['op_type']);
                        } 
                    }
                    
                    //更新用户等级
//                     $userModel->UpdateUserRank($val['user_id'], $val['path'], $rankConf);
                }
                
                if ($logIds) {
                    $logIds = array_chunk($logIds, 1000);
                    foreach ($logIds as $ids) {
                        PowerEvent::query()->whereIn('id', $ids)->update(['is_sync'=>1]);
                    }
                }   
            }
            
            $MyRedis = new MyRedis();
            $MyRedis->del_lock($lockKey);
        }
    }
    
    public function setUserList($user_id = 0)
    {
        if (!isset($this->userList[$user_id]))
        {
            $this->userList[$user_id] = [
                'user_id' => $user_id,
                'usdt' => '0'
            ];
        }
    }
}
