<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Enums\QueueEnum;
use App\Enums\WebsiteAnalyzeEnum;
use App\Http\Controllers\Api\ApiController;
use App\Jobs\UpdateUserPerformanceJob;
use App\Jobs\WithdrawJob;
use App\Models\User;
use App\Models\WebsiteAnalyze;
use App\Models\Withdraw;
use App\Services\Recharge\PaySuccessService;
use App\Services\User\BalanceService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tuupola\Base58;
use App\Models\UserUsdt;
use App\Models\MyRedis;
use GuzzleHttp\Client;
use App\Models\UserMinePower;
use App\Models\UserCastPower;
use App\Models\UserBtc;
use App\Models\UserRwa;
use App\Models\ProductsTakeOrder;
use App\Models\GoldTakeConfig;
use App\Models\UserDhtLock;
use App\Models\UserDht;

class UserController extends ApiController
{

    /**
     * 获取当前用户登录信息
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        try 
        {
            $user = $this->user();
            $data = [
                'address' => $user->address,
                'code' => $user->code,
                'usdt' => $user->usdt,
                'dht' => $user->dht,
                'dht_lock' => $user->dht_lock,
                'rank' => $user->rank,
                'node_rank' => $user->node_rank,
                'zhi_num' => $user->zhi_num,
                'team_num' => $user->team_num,
                'self_yeji' => bcadd($user->self_yeji, '0', 2),
                'team_yeji' => bcadd($user->team_yeji, '0', 2),
                'self_node' => bcadd($user->self_node, '0', 2),
                'team_node' => bcadd($user->team_node, '0', 2),
                'purchased_node' => bcadd($user->self_node, '0', 2),
            ];
            
            
            if($user->path) {
                $path = $user->path."{$user->id}-";
            } else {
                $path = "-{$user->id}-";
            }
            $teamNodeNum = User::query()
                ->where('node_rank', '>', 0)
                ->where('path', 'like', "{$path}%")
                ->count();
            $data['team_node_num'] = $teamNodeNum;
            
            $data['node_rank_name'] = getNodeName($user->node_rank);
            $data['rank_name'] = getRankName($user->rank);
            
            return $this->response($data);
        } catch (\Exception $exception) {
            return $this->__responseError($exception->getMessage(), $exception->getCode());
        }
    }
    
    
    /**
     * 购买节点
     */
    public function goldConfig(Request $request)
    {
        $config = GoldTakeConfig::GetListCache();
        return $this->response($config);
    }
    
    
    public function teamList(Request $request)
    {
        $user = $this->user();
        $in = $request->input();
        
        $pageNum = isset($in['page_num']) && intval($in['page_num'])>0 ? intval($in['page_num']) : 20;
        $page = isset($in['page']) ? intval($in['page']) : 1;
        $page = $page<=0 ? 1 : $page;
        $pageNum = $pageNum>=20 ? 20 : $pageNum;
        $offset = ($page-1)*$pageNum;
        
        $user_id = $user->id;
        if($user->path) {
            $path = $user->path."{$user->id}-";
        } else {
            $path = "-{$user->id}-";
        }
        $list = User::query()
            ->where('parent_id', '=', $user_id)
//             ->where('path', 'like', "{$path}%")
            ->orderBy('deep','asc')
            ->orderBy('id','asc')
            ->offset($offset)
            ->limit($pageNum)
            ->get([
                'address','deep','rank','self_yeji','team_yeji','self_node','team_node','created_at'])
            ->toArray();
        if ($list) {
            foreach ($list as &$val) 
            {
                $val['deep'] = $user->deep-$val['deep'];
                $val['address'] = substr_replace($val['address'],'*****', 3, -3);
                $val['created_at'] = date('Y-m-d H:i:s', strtotime($val['created_at']));
            }
        }
        return $this->response($list);
    }
    
   
    
    public function dhtLog(Request $request): JsonResponse
    {
        $user = $this->user();
        $in = $request->input();
        
        $pageNum = isset($in['page_size']) && intval($in['page_size'])>0 ? intval($in['page_size']) : 10;
        $page = isset($in['page']) ? intval($in['page']) : 1;
        $page = $page<=0 ? 1 : $page;
        $offset = ($page-1)*$pageNum;
        
        $where['user_id'] = $user->id;
        
        $list = UserDht::query()
            ->where($where)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($pageNum)
            ->get(['id','type','cate','total','msg','created_at'])
            ->toArray();
        if ($list) {
            foreach ($list as &$val) {
                $val['msg'] = Lang($val['msg']);
            }
        }
        return $this->response($list);
    }
    
    public function dhtLockLog(Request $request): JsonResponse
    {
        $user = $this->user();
        $in = $request->input();
        
        $pageNum = isset($in['page_size']) && intval($in['page_size'])>0 ? intval($in['page_size']) : 10;
        $page = isset($in['page']) ? intval($in['page']) : 1;
        $page = $page<=0 ? 1 : $page;
        $offset = ($page-1)*$pageNum;
        
        $where['user_id'] = $user->id;
        
        $list = UserDhtLock::query()
            ->where($where)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($pageNum)
            ->get(['id','type','cate','total','msg','created_at'])
            ->toArray();
        if ($list) {
            foreach ($list as &$val) {
                $val['msg'] = Lang($val['msg']);
            }
        }
        return $this->response($list);
    }
    
    /**
     * 挖矿算力日志
     * @param Request $request
     */
    public function usdtLog(Request $request): JsonResponse
    {
        $user = $this->user();
        $in = $request->input();
        
        $pageNum = isset($in['page_size']) && intval($in['page_size'])>0 ? intval($in['page_size']) : 10;
        $page = isset($in['page']) ? intval($in['page']) : 1;
        $page = $page<=0 ? 1 : $page;
        $offset = ($page-1)*$pageNum;
        
        $where['user_id'] = $user->id;
        
        $list = UserUsdt::query()
            ->where($where)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($pageNum)
            ->get(['id','type','cate','total','msg','created_at'])
            ->toArray();
        if ($list) {
            foreach ($list as &$val) {
                $val['msg'] = Lang($val['msg']);
            }
        }
        return $this->response($list);
    }
    
    /**
     * 挖矿算力日志
     * @param Request $request
     */
    public function btcLog(Request $request): JsonResponse
    {
        $user = $this->user();
        $in = $request->input();
        
        $pageNum = isset($in['page_size']) && intval($in['page_size'])>0 ? intval($in['page_size']) : 10;
        $page = isset($in['page']) ? intval($in['page']) : 1;
        $page = $page<=0 ? 1 : $page;
        $offset = ($page-1)*$pageNum;
        
        $where['user_id'] = $user->id;
        
        $list = UserBtc::query()
            ->where($where)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($pageNum)
            ->get(['id','type','cate','total','msg','created_at'])
            ->toArray();
        if ($list) {
            foreach ($list as &$val) {
                $val['msg'] = Lang($val['msg']);
            }
        }
        return $this->response($list);
    }
    
    /**
     * 挖矿算力日志
     * @param Request $request
     */
    public function rwaLog(Request $request): JsonResponse
    {
        $user = $this->user();
        $in = $request->input();
        
        $pageNum = isset($in['page_size']) && intval($in['page_size'])>0 ? intval($in['page_size']) : 10;
        $page = isset($in['page']) ? intval($in['page']) : 1;
        $page = $page<=0 ? 1 : $page;
        $offset = ($page-1)*$pageNum;
        
        $where['user_id'] = $user->id;
        
        $list = UserRwa::query()
            ->where($where)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($pageNum)
            ->get(['id','type','cate','total','msg','created_at'])
            ->toArray();
        if ($list) {
            foreach ($list as &$val) {
                $val['msg'] = Lang($val['msg']);
            }
        }
        return $this->response($list);
    }
}
