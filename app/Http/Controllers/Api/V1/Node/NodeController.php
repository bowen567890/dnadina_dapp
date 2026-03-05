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
        $user = $this->user();
        
        $data['self_yeji'] = '0';
        if ($user) {
            $data['self_yeji'] = $user->self_yeji;
        }
        
        $NodePeriod = NodePeriod::query()
            ->where('id', 1)
            ->first();
        
        $data['price'] = $NodePeriod->total_quota;
        $data['total_quota'] = $NodePeriod->total_quota;
        $data['over_quota'] = bccomp($NodePeriod->over_quota, $NodePeriod->total_quota, 2)>=0 ? $NodePeriod->total_quota : $NodePeriod->over_quota;
        $data['progress_bar'] = bcmul(bcdiv($data['over_quota'], $data['total_quota'], 2), '100', 0);
        
        $data['btime'] = $NodePeriod->btime;
        $data['etime'] = $NodePeriod->etime;
        
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
            
            if (!isset($in['main_chain']) || intval($in['main_chain'])<=0 || !in_array($in['main_chain'], [1,2])) {
                throw new \Exception(Lang('请选择主链'));
            }
            $main_chain = intval($in['main_chain']);
            $id = 1;
            
            $lockKey = 'user:info:'.$user->id;
            $MyRedis = new MyRedis();
            $MyRedis->del_lock($lockKey);
            $lock = $MyRedis->setnx_lock($lockKey, 15);
            if(!$lock){
                throw new \Exception(Lang('操作频繁'));
            }
           
            $datetime = date('Y-m-d H:i:s');
            
            $NodePeriod = NodePeriod::query()
                ->where('id', $id)
//                 ->where('status', 1)
                ->first();
            if (!$NodePeriod) {
                $MyRedis->del_lock($lockKey);
                throw new \Exception(Lang('请选择节点'));
            }
            $price = $NodePeriod->price;
            $over_quota = bcadd($price, $NodePeriod->over_quota, 2);
            if (bccomp($over_quota, $NodePeriod->total_quota, 2)>=0) {
                $MyRedis->del_lock($lockKey);
                throw new \Exception(Lang('节点已认购结束'));
            }
            
            if ($datetime<$NodePeriod->btime) {
                $MyRedis->del_lock($lockKey);
                throw new \Exception(Lang('认购未开始'));
            }
            if ($datetime>$NodePeriod->etime) {
                $MyRedis->del_lock($lockKey);
                throw new \Exception(Lang('认购已截止'));
            }
            
            $topAddress = [
                'direct' => '0x0000000000000000000000000000000000000000'
            ];
           
            
            $direct_uid = $user->parent_id;
            $direct_rate = bcadd(config('direct_node_rate'), '0', 2);
            $direct_num = bcmul($price, $direct_rate, 2);
            if ($direct_uid>0) {
                $topAddress['direct'] = User::query()->where('id', $direct_uid)->value('address');
            }
            
            $ordernum = get_ordernum();
            
            $order = new NodeOrderLog();
            $order->period_id = $NodePeriod->id;
            $order->user_id = $user->id;
            $order->main_chain = $main_chain;
            $order->price = $NodePeriod->price;
            $order->pay_type = 1;
            $order->direct_uid = $direct_uid;
            $order->direct_rate = $direct_rate;
            $order->direct_num = $direct_num;
            $order->ordernum = $ordernum;
            $order->save();
            
            $OrderLog = new OrderLog();
            $OrderLog->ordernum = $ordernum;
            $OrderLog->user_id = $user->id;
            $OrderLog->type = 1;    //订单类型1购买节点
            $OrderLog->save();
            
            $usdtCurrency = MainCurrency::query()->where('name', 'USDT')->where('main_chain', $main_chain)->first(['name','rate','contract_address','precision']);
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
//             $outputCoin = [1=>'USDT', 3=>'BTC'];
//             foreach ($list as &$val) 
//             {
// //                 $val['output_coin'] = $outputCoin[$val['output_coin']];
// //                 $val['mine_income_rate'] = $val['mine_income_rate']*100;
//             }
        }
        return $this->response($list);
    }
    
}
