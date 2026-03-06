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
use App\Models\RankConfig;

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
                'rank' => $user->rank,
                'valid_status' => $user->valid_status,
                'zhi_num' => $user->zhi_num,
                'zhi_valid' => $user->zhi_valid,
                'team_num' => $user->team_num,
                'self_yeji' => bcadd($user->self_yeji, '0', 2),
                'zhi_yeji' => bcadd($user->zhi_yeji, '0', 2),
                'team_yeji' => bcadd($user->team_yeji, '0', 2),
            ];
            
            $data['rank_txt'] = '';
            $data['rank_img'] = '';
            $rankConfig = RankConfig::GetListCache();
            $rankConfig = array_column($rankConfig, null, 'lv');
            if (isset($rankConfig[$user->rank])) {
                $data['rank_txt'] = LocalDataGet($rankConfig[$user->rank]['name']);
                $data['rank_img'] = getImageUrl($rankConfig[$user->rank]['image']);
            }
            
            return $this->response($data);
        } catch (\Exception $exception) {
            return $this->__responseError($exception->getMessage(), $exception->getCode());
        }
    }
    
    public function zhiList(Request $request)
    {
        $user = $this->user();
        $in = $request->input();
        
        $pageNum = isset($in['page_size']) && intval($in['page_size'])>0 ? intval($in['page_size']) : 20;
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
                'address','self_yeji','team_yeji','created_at'])
            ->toArray();
        if ($list) {
            foreach ($list as &$val)
            {
                $val['address'] = substr_replace($val['address'],'*****', 3, -3);
                $val['created_at'] = date('Y-m-d H:i:s', strtotime($val['created_at']));
            }
        }
        return $this->response($list);
    }
    
    
    public function teamList(Request $request)
    {
        $user = $this->user();
        $in = $request->input();
        
        $pageNum = isset($in['page_size']) && intval($in['page_size'])>0 ? intval($in['page_size']) : 20;
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
//             ->where('parent_id', '=', $user_id)
            ->where('path', 'like', "{$path}%")
            ->orderBy('deep','asc')
            ->orderBy('id','asc')
            ->offset($offset)
            ->limit($pageNum)
            ->get([
                'address','self_yeji','team_yeji','created_at'])
            ->toArray();
        if ($list) {
            foreach ($list as &$val) 
            {
                $val['address'] = substr_replace($val['address'],'*****', 3, -3);
                $val['created_at'] = date('Y-m-d H:i:s', strtotime($val['created_at']));
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
    
}
