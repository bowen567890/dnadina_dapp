<?php

namespace App\Http\Controllers\Api\V1\Node;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Enums\IncomeTypeEnum;
use App\Enums\UserCoinAmountTypeEnum;
use App\Http\Controllers\Api\ApiController;
use App\Models\Currency;
use App\Services\AppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Models\LoanMonth;
use App\Models\MyRedis;
use App\Models\MainCurrency;
use App\Models\LoanOrdersLog;
use App\Models\OrderLog;
use App\Models\LoanRepayment;
use App\Models\LoanOrder;
use App\Models\LoanRepaymentsLog;
use App\Models\LoanRepaymentsFull;
use GuzzleHttp\Client;
use App\Models\User;
use App\Models\LoanEvent;
use App\Models\LoanLeverage;
use App\Models\LoanMultiple;
use App\Models\NodeConfig;
use App\Models\NodeOrderLog;
use App\Models\NodeOrder;
use App\Models\RankConfig;
use App\Models\NodePeriod;
use App\Models\DhtLockOrder;

class NodeController extends ApiController
{

    /**
     * 借款配置
     * @return JsonResponse
     * @throws \Throwable
     */
    public function config()
    {
//         $user = $this->user();
        
        $data['node_sell_btime'] = config('node_sell_btime');
        $data['node_sell_etime'] = config('node_sell_etime');
        
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
        return $this->response($data);
    }
    
    /**
     * 购买节点
     */
    public function buy(Request $request)
    {
        try 
        {
            $on_chain_pay = config('env.ON_CHAIN_PAY');
            if (!$on_chain_pay) {
                throw new \Exception(Lang('敬请期待'));
            }
            
            $fStatus = intval(config('buy_node_status'));
            if ($fStatus!=1) {
                throw new \Exception(Lang('敬请期待'));
            }
            
            $user = $this->user();
            $in = $request->input();
            
            if (!isset($in['id']) || intval($in['id'])<=0) {
                throw new \Exception(Lang('请选择节点'));
            }
            $id = intval($in['id']);
            
            $lockKey = 'user:info:'.$user->id;
            $MyRedis = new MyRedis();
//             $MyRedis->del_lock($lockKey);
            $lock = $MyRedis->setnx_lock($lockKey, 15);
            if(!$lock){
                throw new \Exception(Lang('操作频繁'));
            }
           
            $datetime = date('Y-m-d H:i:s');
            $node_sell_btime = config('node_sell_btime');
            $node_sell_etime = config('node_sell_etime');
            
            if ($datetime<$node_sell_btime) {
                $MyRedis->del_lock($lockKey);
                throw new \Exception(Lang('抢购未开始'));
            }
            if ($datetime>$node_sell_etime) {
                $MyRedis->del_lock($lockKey);
                throw new \Exception(Lang('抢购已截止'));
            }
            
            $NodePeriod = NodePeriod::query()
                ->where('id', $id)
                ->where('status', 1)
                ->first();
            if (!$NodePeriod) {
                $MyRedis->del_lock($lockKey);
                throw new \Exception(Lang('请选择节点'));
            }
            
            if ($NodePeriod->stock<=0) {
                $MyRedis->del_lock($lockKey);
                throw new \Exception(Lang('节点库存不足'));
            }
            $lv = $NodePeriod->lv;
            
            //判断不同购买同一个节点
            $NodeOrder = NodeOrder::query()
                ->where('user_id', $user->id)
                ->where('lv', $lv)
                ->first();
            if ($NodeOrder) {
                $MyRedis->del_lock($lockKey);
                throw new \Exception(Lang('同一等级节点限购一次'));
            }
            
            $topAddress = [
                'direct' => '0x0000000000000000000000000000000000000000',
                'indirect' => '0x0000000000000000000000000000000000000000'
            ];
            
            
            $direct_uid = $indirect_uid = 0;
            $direct_rate = $indirect_rate = $trade_rate = $company_rate = $divvy_rate = '0';
            $direct_num = $indirect_num = $trade_num = $company_num = $divvy_num = '0';
            
            $totalRate = '1';
            $trade_rate = '0.2';
            $trade_num = bcmul($NodePeriod->price, $trade_rate, 2);
            
            $divvy_rate = '0.05';
            $divvy_num = bcmul($NodePeriod->price, $divvy_rate, 2);
            
            //判断伞下节点人数
            if($user->path) 
            {
                $parentIds = explode('-',trim($user->path,'-'));
                $parentIds = array_reverse($parentIds);
                $parentIds = array_filter($parentIds);
                if ($parentIds) 
                {
                    $plist = User::query()
                        ->where('node_rank', '>', 0)
                        ->whereIn('id', $parentIds)
                        ->orderBy('deep', 'desc')
                        ->limit(2)
                        ->get(['id','address'])
                        ->toArray();
                    if (isset($plist[0])) 
                    {
                        $direct_uid = $plist[0]['id'];
                        $topAddress['direct'] = $plist[0]['address'];
                        $direct_rate = '0.2';
                        $direct_num = bcmul($NodePeriod->price, $direct_rate, 2);
//                         $direct_rate = @bcadd(config('direct_node_rate'), '0', 3);
//                         $direct_rate = getRate($direct_rate);
                    }
                    if (isset($plist[1])) {
                        $indirect_uid = $plist[1]['id'];
                        $topAddress['indirect'] = $plist[1]['address'];
//                         $indirect_rate = @bcadd(config('indirect_node_rate'), '0', 3);
//                         $indirect_rate = getRate($indirect_rate);
                        $indirect_rate = '0.05';
                        $indirect_num = bcmul($NodePeriod->price, $indirect_rate, 2);
                    }
                }
            }
            
            $tmpRate1 = bcadd($trade_rate, $divvy_rate, 3);
            $tmpRate2 = bcadd($direct_rate, $indirect_rate, 3);
            $company_rate = bcsub($totalRate, bcadd($tmpRate1, $tmpRate2, 3), 3);
            
            $tmpNum1 = bcadd($trade_num, $divvy_num, 3);
            $tmpNum2 = bcadd($direct_num, $indirect_num, 3);
            $company_num = bcsub($NodePeriod->price, bcadd($tmpNum1, $tmpNum2, 2), 2);
            
//             var_dump($direct_uid,$indirect_uid,$direct_rate,$indirect_rate,$trade_rate,$company_rate);
//             var_dump($direct_num,$indirect_num,$trade_num,$company_num);die;
            
            $ordernum = get_ordernum();
            
            $order = new NodeOrderLog();
            $order->lv = $lv;
            $order->period_id = $NodePeriod->id;
            $order->user_id = $user->id;
            $order->price = $NodePeriod->price;
            $order->give_lock_dht = $NodePeriod->give_lock_dht;
            $order->fee_day = $NodePeriod->fee_day;
            $order->pay_type = 1;
            $order->give_rank = $NodePeriod->give_rank;
            $order->direct_uid = $direct_uid;
            $order->indirect_uid = $indirect_uid;
            $order->direct_rate = $direct_rate;
            $order->indirect_rate = $indirect_rate;
            $order->divvy_rate = $divvy_rate;
            $order->trade_rate = $trade_rate;
            $order->company_rate = $company_rate;
            $order->direct_num = $direct_num;
            $order->indirect_num = $indirect_num;
            $order->divvy_num = $divvy_num;
            $order->trade_num = $trade_num;
            $order->company_num = $company_num;
            $order->ordernum = $ordernum;
            $order->save();
            
            $OrderLog = new OrderLog();
            $OrderLog->ordernum = $ordernum;
            $OrderLog->user_id = $user->id;
            $OrderLog->type = 1;    //订单类型1购买节点
            $OrderLog->save();
            
            $usdtCurrency = MainCurrency::query()->where('id', 1)->first();
            $usdtCurrency = MainCurrency::query()->where('id', 1)->first(['name','rate','contract_address','precision']);
            $contract_address = strtolower($usdtCurrency->contract_address);
            
            $MyRedis->del_lock($lockKey);
            return $this->response([
                'order_no' => $ordernum,
                'currency' => [
                    'name' => $usdtCurrency->name,
                    'contract_address' => $contract_address,
                    'decimals' => $usdtCurrency->precision,
                ],
                'amount' => $NodePeriod->price,
                'list' => $topAddress
//                 'amount' => '0.01'
            ]);
        } 
        catch (\Exception $e)
        {
//             $MyRedis->del_lock($lockKey);
//             return $this->__responseError($e->getMessage().$e->getLine(), $e->getCode());
            return $this->__responseError($e->getMessage(), $e->getCode());
        }
    }

    public function buyList(Request $request)
    {
        $user = $this->user();
        $in = $request->input();
        
        $pageNum = isset($in['page_size']) && intval($in['page_size'])>0 ? intval($in['page_size']) : 10;
        $page = isset($in['page']) ? intval($in['page']) : 1;
        $page = $page<=0 ? 1 : $page;
        $offset = ($page-1)*$pageNum;
        
        $where['user_id'] = $user->id;
//         $where['is_del'] = 0;
        
        $list = NodeOrder::query()
            ->where($where)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($pageNum)
            ->get()
            ->toArray();
        if ($list) 
        {
            $NodeConfig = NodeConfig::GetListCache();
            $RankConfig = RankConfig::GetListCache();
            $outputCoin = [1=>'USDT', 3=>'BTC'];
            foreach ($list as &$val) 
            {
                $val['lv_name'] = getNodeName($val['lv'], $NodeConfig);
                $val['give_rank_name'] = getRankName($val['give_rank'], $RankConfig);
//                 $val['output_coin'] = $outputCoin[$val['output_coin']];
//                 $val['mine_income_rate'] = $val['mine_income_rate']*100;
            }
        }
        return $this->response($list);
    }
    
    public function lockOrder(Request $request)
    {
        $user = $this->user();
        $in = $request->input();
        
        $pageNum = isset($in['page_size']) && intval($in['page_size'])>0 ? intval($in['page_size']) : 10;
        $page = isset($in['page']) ? intval($in['page']) : 1;
        $page = $page<=0 ? 1 : $page;
        $offset = ($page-1)*$pageNum;
        
        $where['user_id'] = $user->id;
        //         $where['is_del'] = 0;
        
        $list = DhtLockOrder::query()
            ->where($where)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($pageNum)
            ->get()
            ->toArray();
        if ($list)
        {
//             foreach ($list as &$val)
//             {
//                 $val['lv_name'] = getNodeName($val['lv'], $NodeConfig);
//                 $val['give_rank_name'] = getRankName($val['give_rank'], $RankConfig);
//                 //                 $val['output_coin'] = $outputCoin[$val['output_coin']];
//                 //                 $val['mine_income_rate'] = $val['mine_income_rate']*100;
//             }
        }
        return $this->response($list);
    }
}
