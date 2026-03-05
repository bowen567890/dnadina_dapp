<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\MyRedis;
use App\Models\Banner;
use App\Models\User;
use App\Models\MainCurrency;
use App\Models\OrderLog;
use GuzzleHttp\Client;
use App\Models\Withdraw;
use App\Models\LoanRepayment;
use App\Models\LoanOrder;
use App\Models\NodeConfig;
use App\Models\Common\Notice;

class IndexController extends ApiController
{
    public function index(Request $request)
    {
        $user = $this->user();
        
        $data['address'] = '';
        
        if ($user) 
        {
            $data['address'] = $user->address;
        }
        
        
        $node_list = NodeConfig::with(['grank'])
            ->join('node_period as p', 'node_config.lv', '=', 'p.lv')
            ->where('p.status', 1)
            ->get([
                'p.id','p.lv','node_config.name','p.price','p.give_rank','p.stock','p.sales',
                'p.give_lock_dht','p.fee_day','p.total_quantity','node_config.image'
            ])
            ->toArray();
        if ($node_list)
        {
            foreach ($node_list as &$nval)
            {
                $nval['name'] = LocalDataGet($nval['name']);
                $nval['give_rank_txt'] = '';
                $nval['image'] = getImageUrl($nval['image']);
                if ($nval['grank']) {
                    $nval['give_rank_txt'] = $nval['grank']['name'];
                }
                $nval['stock'] = $nval['stock']<=0 ? 0 : $nval['stock'];
                $nval['total_quantity'] = $nval['stock']+$nval['sales'];
                unset($nval['grank'], $nval['sales']);
            }
        }
        
        $data['node_list'] = $node_list;
        
        $data['is_pop'] = 0;
        $data['notice'] = [];
        
        if ($user)
        {
            //首页新商品弹框
            $notice = Notice::query()
            ->where('status', 1)
            ->where('ispop', 1)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->first(['id','title','content','updated_at']);
            if ($notice)
            {
                $notice = $notice->toArray();
                $notice['title'] = LocalDataGet($notice['title']);
                $notice['content'] = LocalDataGet($notice['content']);
                
                $MyRedis = new MyRedis();
                
                $lockKey = 'notice:uid:'.$user->uid;
                $noticeId = $MyRedis->get_key($lockKey);
                $noticeId = intval($noticeId);
                
                $lockPopKey = 'notice:nid:'.$user->uid;
                //             RedisUtil::delLock($lockPopKey);
                
                if ($notice['id']!=$noticeId) {
                    $MyRedis->set_key($lockKey, $notice['id']);
                    $MyRedis->del_lock($lockPopKey);
                }
                $plock = $MyRedis->setnx_lock($lockPopKey, 86400);
                if ($plock) {
                    $data['is_pop'] = 1;
                    $data['notice'] = $notice;
                }
//                 $data['is_pop'] = 1;
//                 $data['notice'] = $notice;
            }
        }
        
        return $this->response($data);
    }
}
