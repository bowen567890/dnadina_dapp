<?php

namespace App\Services\Recharge;


use App\Enums\QueueEnum;
use App\Enums\UserPerformanceTypeEnum;
use App\Enums\WebsiteAnalyzeEnum;
use App\Jobs\SettlementMachineIncomeJob;
use App\Jobs\UpdateUserPerformanceJob;
use App\Models\Currency;
use App\Models\RechargeModel;
use App\Models\User;
use App\Models\WebsiteAnalyze;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\OrderLog;
use App\Models\MyRedis;
use App\Models\NodeOrderLog;
use App\Models\NodeOrder;
use App\Models\NodeConfig;
use App\Models\PowerEvent;
use App\Models\ProductsOrderLog;
use App\Models\ProductsOrder;
use App\Models\Product;
use App\Models\FundPool;
use App\Models\ProductsStatistic;
use App\Models\WebsiteAnalyzeDaily;
use App\Models\WebsiteStatistic;
use App\Models\NodePeriod;
use App\Models\DhtLockOrder;

class RechargeService extends BaseService
{

    public function walletRecharge($data)
    {
        $ordernum = isset($data['remarks']) && $data['remarks'] ? $data['remarks'] : '';
//         Log::channel('recharge_callback')->info('收到回调', $data);
        $order = OrderLog::query()->where('ordernum', $ordernum)->first();
        if (!$order) {
            return $this->responseError('订单不存在');
        }
        $order->content = json_encode($data);
        $order->save();
        
        //订单类型1购买节点
        if ($order->type == 1) {
            $this->buyNode($data);
        }
    }

    /**
     * 购买节点
     */
    public function buyNode($in)
    {
        try
        {
            $ordernum = $in['remarks'];
            $lockKey = 'callback:buyNode:'.$ordernum;
            $MyRedis = new MyRedis();
//                                                     $MyRedis->del_lock($lockKey);
            $ret = $MyRedis->setnx_lock($lockKey, 60);
            if(!$ret){
                Log::channel('buy_node')->info('上锁失败', $in);
                throw new \Exception('上锁失败');
            }
            
            $order = NodeOrderLog::query()->where(['ordernum'=>$ordernum, 'pay_status'=>0])->first();
            if (!$order) {
                Log::channel('buy_node')->info('订单不存在', $in);
                $MyRedis->del_lock($lockKey);
                throw new \Exception('订单不存在');
            }
            if (!isset($in['coin_token']) || $in['coin_token']!='USDT') {
                Log::channel('buy_node')->info('币种不正确', $in);
                $MyRedis->del_lock($lockKey);
                throw new \Exception('币种不正确');
            }
            
            $hash = isset($in['hash']) && $in['hash'] ? $in['hash'] : '';
            $amount = @bcadd($in['amount'], '0', 2);
            
            //支付类型1USDT(链上)
            if (bccomp($order->price, $amount, 2)>0)
            {
                if ($in['status']==3 && $order->pay_status==0) {
                    Log::channel('buy_node')->info('金额有误', $in);
                    $order->pay_status = 2;
                    $order->hash = $hash;
                    $order->save();
                }
                $MyRedis->del_lock($lockKey);
                throw new \Exception('金额有误');
            }
       
            if ($in['status']==3 && $order->pay_status==0)
            {
                DB::beginTransaction();
                try 
                {     
                    $time = time();
                    $date = date('Y-m-d', $time);
                    $datetime = date('Y-m-d H:i:s', $time);
                    
                    $user = User::query()->where('id', $order->user_id)->first(['id','rank','hold_rank','give_rank','node_rank']);
                    
                    $order->pay_status = 1;
                    $order->hash = $hash;
                    $order->save();
                    
                    $NodeOrder = new NodeOrder();
                    $NodeOrder->lv = $order->lv;
                    $NodeOrder->period_id = $order->period_id;
                    $NodeOrder->user_id = $order->user_id;
                    $NodeOrder->price = $order->price;
                    $NodeOrder->give_lock_dht = $order->give_lock_dht;
                    $NodeOrder->fee_day = $order->fee_day;
                    $NodeOrder->pay_type = $order->pay_type;
                    $NodeOrder->give_rank = $order->give_rank;
                    $NodeOrder->ordernum = $order->ordernum;
                    $NodeOrder->source_type = $order->source_type;  //节点来源1购买2后台
                    $NodeOrder->direct_uid = $order->direct_uid;
                    $NodeOrder->indirect_uid = $order->indirect_uid;
                    $NodeOrder->direct_rate = $order->direct_rate;
                    $NodeOrder->indirect_rate = $order->indirect_rate;
                    $NodeOrder->divvy_rate = $order->divvy_rate;
                    $NodeOrder->trade_rate = $order->trade_rate;
                    $NodeOrder->company_rate = $order->company_rate;
                    $NodeOrder->direct_num = $order->direct_num;
                    $NodeOrder->indirect_num = $order->indirect_num;
                    $NodeOrder->divvy_num = $order->divvy_num;
                    $NodeOrder->trade_num = $order->trade_num;
                    $NodeOrder->company_num = $order->company_num;
                    $NodeOrder->hash = $hash;
                    $NodeOrder->save();
                    
                    NodePeriod::query()->where('id', $order->period_id)->update([
                        'stock' => DB::raw("`stock`-1"),
                        'sales' => DB::raw("`sales`+1")
                    ]);
                    
                    $uup = [];
                    
                    if ($order->give_rank>$user->give_rank) {
                        $uup['give_rank'] = $order->give_rank;
                    }
                    if ($order->give_rank>$user->rank) {
                        $uup['rank'] = $order->give_rank;
                    }
                    if ($order->lv>$user->node_rank) {
                        $uup['node_rank'] = $order->lv;
                    }
                    if ($uup) {
                        User::query()->where('id', $order->user_id)->update($uup);
                    }
                    
                    $userModel = new User();
                    if (bccomp($order->give_lock_dht, '0', 2)>0 && $order->fee_day>0)
                    {
                        //分类1系统增加2系统扣除5锁仓释放6购买节点
                        $cates = ['msg'=>'购买节点', 'cate'=>6, 'ordernum'=>$order->ordernum];
                        $userModel->handleUser('dht_lock', $order->user_id, $order->give_lock_dht, 1, $cates);
                        
                        $daily_num = bcdiv($order->give_lock_dht, $order->fee_day, 6);
                        $DhtLockOrder = new DhtLockOrder();
                        $DhtLockOrder->user_id = $order->user_id;
                        $DhtLockOrder->node_oid = $NodeOrder->id;
                        $DhtLockOrder->total_day = $order->fee_day;
                        $DhtLockOrder->wait_day = $order->fee_day;
                        $DhtLockOrder->total_num = $order->give_lock_dht;
                        $DhtLockOrder->wait_num = $order->give_lock_dht;
                        $DhtLockOrder->daily_num = $daily_num;
                        $DhtLockOrder->ordernum = $order->ordernum;
                        $DhtLockOrder->save();
                        
                        $NodeOrder->lock_oid = $DhtLockOrder->id;
                        $NodeOrder->save();
                    }
//                     if (bccomp($order->indirect_num, '0', 2)>0 && $order->indirect_uid>0)
//                     {
//                         //分类1系统增加2系统扣除3提币扣除4提币驳回5直推节点6间推节点
//                         $cates = ['msg'=>'间推节点', 'cate'=>6, 'ordernum'=>$order->ordernum, 'from_user_id'=>$order->user_id];
//                         $userModel->handleUser('usdt', $order->indirect_uid, $order->indirect_num, 1, $cates);
//                     }
                    
                    $PowerEvent = new PowerEvent();
                    $PowerEvent->user_id = $order->user_id;
                    $PowerEvent->order_id = $NodeOrder->id;
                    $PowerEvent->type = 1;  //事件类型1节点事件
                    $PowerEvent->usdt = $order->price;
                    $PowerEvent->ordernum = $order->ordernum;
                    $PowerEvent->save();
                    
                    if ($order->trade_num>0) {
                        FundPool::query()->where('type', 1)->increment('amount', $order->trade_num);
                    }
                    if ($order->divvy_num>0) {
                        FundPool::query()->where('type', 2)->increment('amount', $order->divvy_num);
                    }
                    
                    if ($order->source_type==1) 
                    {
                        WebsiteAnalyzeDaily::query()
                            ->where('date', $date)
                            ->update(
                                [
                                    'recharge_usdt' => DB::raw("`recharge_usdt`+{$order->price}"),
                                    'recharge_usdt_node' => DB::raw("`recharge_usdt_node`+{$order->price}")
                                ]
                            );
                            
                        WebsiteStatistic::query()
                            ->where('id', 1)
                            ->update(
                                [
                                    'recharge_usdt' => DB::raw("`recharge_usdt`+{$order->price}"),
                                    'recharge_usdt_node' => DB::raw("`recharge_usdt_node`+{$order->price}")
                                ]
                            );
                    }
                    
                    DB::commit();
                }
                catch (\Exception $e)
                {
                    DB::rollBack();
                    $MyRedis->del_lock($lockKey);
                    throw new \Exception($e->getMessage());
                }
            }
            $MyRedis->del_lock($lockKey);
//             //支付成功的操作
//             PaySuccessService::getService()->buyMachineSuccess($user,$extend['num'],$data['amount']);
//             UpdateUserPerformanceJob::dispatch($user->id,UserPerformanceTypeEnum::BUY_MACHINE,[
//                 'performance' => $extend['num'],
//             ],true)->onQueue(QueueEnum::UserLevel);
//            结算直推奖，层级奖
//            SettlementMachineIncomeJob::dispatch($response['machine_id'])->onQueue(QueueEnum::Settlement);
        } catch (\Throwable $exception) {
//             var_dump($exception->getMessage());
            Log::channel('buy_node')->info('购买节点订单处理失败'.$exception->getMessage());
        }
    }
    
    /**
     * 商品合约订单
     */
    public function buyProduct($in)
    {
        try
        {
            $ordernum = $in['remarks'];
            $lockKey = 'callback:buyProduct:'.$ordernum;
            $MyRedis = new MyRedis();
//                                                                 $MyRedis->del_lock($lockKey);
            $ret = $MyRedis->setnx_lock($lockKey, 60);
            if(!$ret){
                Log::channel('buy_product')->info('上锁失败', $in);
                throw new \Exception('上锁失败');
            }
            
            $order = ProductsOrderLog::query()->where(['ordernum'=>$ordernum, 'pay_status'=>0])->first();
            if (!$order) {
                Log::channel('buy_product')->info('订单不存在', $in);
                $MyRedis->del_lock($lockKey);
                throw new \Exception('订单不存在');
            }
            if (!isset($in['coin_token']) || $in['coin_token']!='USDT') {
                Log::channel('buy_product')->info('币种不正确', $in);
                $MyRedis->del_lock($lockKey);
                throw new \Exception('币种不正确');
            }
            
            $hash = isset($in['hash']) && $in['hash'] ? $in['hash'] : '';
            $amount = @bcadd($in['amount'], '0', 2);
            
            //支付类型1USDT(链上)
            if (bccomp($order->price, $amount, 2)>0)
            {
                if ($in['status']==3 && $order->pay_status==0) {
                    Log::channel('buy_product')->info('金额有误', $in);
                    $order->pay_status = 2;
                    $order->hash = $hash;
                    $order->save();
                }
                $MyRedis->del_lock($lockKey);
                throw new \Exception('金额有误');
            }
            
            
            if ($in['status']==3 && $order->pay_status==0)
            {
                DB::beginTransaction();
                try
                {
                    $time = time();
                    $date = date('Y-m-d', $time);
                    $datetime = date('Y-m-d H:i:s', $time);
                    
                    $user = User::query()->where('id', $order->user_id)->first(['id','valid_status','parent_id']);
                    
                    $order->pay_status = 1;
                    $order->hash = $hash;
                    $order->save();
                    
                    $ProductsOrder = new ProductsOrder();
                    $ProductsOrder->user_id = $order->user_id;
                    $ProductsOrder->product_id = $order->product_id;
                    $ProductsOrder->contract_id = $order->contract_id;
                    $ProductsOrder->price = $order->price;
                    $ProductsOrder->unit_price = $order->unit_price;
                    $ProductsOrder->give_gold = $order->give_gold;
                    $ProductsOrder->total_gold = $order->total_gold;
                    $ProductsOrder->num = $order->num;
                    $ProductsOrder->pay_type = $order->pay_type;
                    $ProductsOrder->mine_power = $order->mine_power;
                    $ProductsOrder->cast_power = $order->cast_power;
                    $ProductsOrder->wait_mine_power = $order->mine_power;
                    $ProductsOrder->wait_cast_power = $order->cast_power;
                    $ProductsOrder->mine_fund = $order->mine_fund;
                    $ProductsOrder->cast_fund = $order->cast_fund;
                    $ProductsOrder->market_fund = $order->market_fund;
                    $ProductsOrder->mine_power_multiple = $order->mine_power_multiple;
                    $ProductsOrder->cast_power_multiple = $order->cast_power_multiple;
                    $ProductsOrder->mine_income_rate = $order->mine_income_rate;
                    $ProductsOrder->power_fund_rate = $order->power_fund_rate;
                    $ProductsOrder->cast_market_rate = $order->cast_market_rate;
                    $ProductsOrder->cast_fund_rate = $order->cast_fund_rate;
                    $ProductsOrder->market_fund_rate = $order->market_fund_rate;
                    $ProductsOrder->output_coin = $order->output_coin;
                    $ProductsOrder->monthly_income_rate = $order->monthly_income_rate;
                    $ProductsOrder->need_deliver = $order->need_deliver;
                    $ProductsOrder->deliver_linkman = $order->deliver_linkman;
                    $ProductsOrder->deliver_tel = $order->deliver_tel;
                    $ProductsOrder->deliver_address = $order->deliver_address;
                    $ProductsOrder->deliver_status = $order->need_deliver==1 ? 1 : 0;   //订单状态0无需发货1待发货2已发货3已签收
                    $ProductsOrder->ordernum = $order->ordernum;
                    $ProductsOrder->hash = $hash;
                    $ProductsOrder->hash = $hash;
                    $ProductsOrder->source_type = $order->source_type;    //节点来源1购买2后台
                    $ProductsOrder->save();
                    
                    //库存
                    Product::query()->where('id', $order->product_id)->update([
                        'stock' => DB::raw("`stock`-1"),
                        'sales' => DB::raw("`sales`+1")
                    ]);
                    
                    $sUp = [];
                    $sUp['total_fund'] = DB::raw("`total_fund`+{$order->price}");
                    
                    //铸币池子 池子类型1铸币资金池2市值资金池3算力资金池
                    if (bccomp($order->cast_fund, '0', 2)>0) {
                        FundPool::query()->where('type', 1)->increment('amount', $order->cast_fund);
                        $sUp['cast_fund'] = DB::raw("`cast_fund`+{$order->cast_fund}");
                    }
                    if (bccomp($order->market_fund, '0', 2)>0) {
                        FundPool::query()->where('type', 2)->increment('amount', $order->market_fund);
                        $sUp['market_fund'] = DB::raw("`market_fund`+{$order->market_fund}");
                    }
                    if (bccomp($order->mine_fund, '0', 2)>0) {
                        FundPool::query()->where('type', 3)->increment('amount', $order->mine_fund);
                        $sUp['mine_fund'] = DB::raw("`mine_fund`+{$order->mine_fund}");
                    }
                    
                    //合约每日统计
                    ProductsStatistic::query()->where('date', $date)->update($sUp);
                    
                    //有效用户
                    if ($user->valid_status==0) {
                        $user->valid_status = 1;
                        $user->save();
                        if ($user->parent_id>0) {
                            User::query()->where('id', $user->parent_id)->increment('zhi_valid', 1);
                        }
                    }
                    
                    $userModel = new User();
                    if (bccomp($order->mine_power, '0', 2)>0)
                    {
                        //分类1系统增加2系统扣除3购买节点4购买合约
                        $cates = ['msg'=>'购买合约', 'cate'=>4, 'ordernum'=>$order->ordernum];
                        $userModel->handleUser('mine_power', $order->user_id, $order->mine_power, 1, $cates);
                    }
                    if (bccomp($order->cast_power, '0', 2)>0) {
                        //分类1系统增加2系统扣除3购买节点4购买合约
                        $cates = ['msg'=>'购买合约', 'cate'=>4, 'ordernum'=>$order->ordernum];
                        $userModel->handleUser('cast_power', $order->user_id, $order->cast_power, 1, $cates);
                    }
                    if (bccomp($order->total_gold, '0', 2)>0) {
                        //分类1系统增加2系统扣除4购买合约5提货扣除
                        $cates = ['msg'=>'购买合约', 'cate'=>4, 'ordernum'=>$order->ordernum];
                        $userModel->handleUser('gold', $order->user_id, $order->total_gold, 1, $cates);
                    }
                    
                    $PowerEvent = new PowerEvent();
                    $PowerEvent->user_id = $order->user_id;
                    $PowerEvent->order_id = $ProductsOrder->id;
                    $PowerEvent->type = 2;  //事件类型1节点事件2购买合约3挖矿扣除
                    $PowerEvent->usdt = $order->price;
                    $PowerEvent->mine_power = $order->mine_power;
                    $PowerEvent->cast_power = $order->cast_power;
                    $PowerEvent->ordernum = $order->ordernum;
                    $PowerEvent->save();
                    
                    if ($order->source_type==1)
                    {
                        WebsiteAnalyzeDaily::query()
                            ->where('date', $date)
                            ->update(
                                [
                                    'recharge_usdt' => DB::raw("`recharge_usdt`+{$order->price}"),
                                    'recharge_usdt_contract' => DB::raw("`recharge_usdt_contract`+{$order->price}")
                                ]
                            );
                        
                        WebsiteStatistic::query()
                            ->where('id', 1)
                            ->update(
                                [
                                    'recharge_usdt' => DB::raw("`recharge_usdt`+{$order->price}"),
                                    'recharge_usdt_contract' => DB::raw("`recharge_usdt_contract`+{$order->price}")
                                ]
                            );
                    }
                    
                    DB::commit();
                }
                catch (\Exception $e)
                {
                    DB::rollBack();
                    $MyRedis->del_lock($lockKey);
                    throw new \Exception($e->getMessage());
                }
            }
            $MyRedis->del_lock($lockKey);
            //             //支付成功的操作
            //             PaySuccessService::getService()->buyMachineSuccess($user,$extend['num'],$data['amount']);
            //             UpdateUserPerformanceJob::dispatch($user->id,UserPerformanceTypeEnum::BUY_MACHINE,[
            //                 'performance' => $extend['num'],
            //             ],true)->onQueue(QueueEnum::UserLevel);
            //            结算直推奖，层级奖
            //            SettlementMachineIncomeJob::dispatch($response['machine_id'])->onQueue(QueueEnum::Settlement);
        } catch (\Throwable $exception) {
            Log::channel('buy_product')->info('购买节点订单处理失败'.$exception->getMessage());
            }
    }
    /**
     * 购买节点逻辑处理
     * @param $user
     * @param $order
     * @param $data
     * @return void
     */
    public function nodeHandle($user,$order,$data): void
    {
        Log::channel('recharge_callback')->info('开始处理节点订单');
        try {
            $extend = json_decode($order['extend'],true);
            $node = Node::query()->where('id',$extend['node_id'])->first();

            $powerPrice = Setting('power_price');
            if (empty($powerPrice)){
                throw new \Exception("未找到算力价格");
            }
            DB::beginTransaction();
            try {
                $order->status = 2;
                $order->hash = $data['hash'];
                $order->save();

                RechargeModel::query()->create([
                    'user_id' => $user->id,
                    'order_no' => $order->order_no,
                    'type' => 1,
                    'nums' => $data['amount'],
                    'other_nums' => empty($data['amount1']) ? 0 : $data['amount1'],
                    'coin' => $data['coin_token'],
                    'other_coin' => empty($data['contract_address1']) ? '' : strtoupper(Currency::query()->where('contract_address',$data['contract_address1'])->value('name')),
                    'hash' => $data['hash'],
                    'detail' => json_encode($data,true)
                ]);

                WebsiteAnalyze::addData(WebsiteAnalyzeEnum::RECHARGE_USDT_NUM,$data['amount']);
                WebsiteAnalyze::addData(WebsiteAnalyzeEnum::RECHARGE_USDT_COUNT,1);

                //支付成功的操作
                PaySuccessService::getService()->buyNodeSuccess($user,$node,$data['hash']);
                DB::commit();
                if ($node->gift_power > 0){
                    UpdateUserPerformanceJob::dispatch($user->id,UserPerformanceTypeEnum::BUY_NODE,[
                        'performance' => $node->gift_power,
                    ],true)->onQueue(QueueEnum::UserLevel);
                }
                Log::channel('recharge_callback')->info('节点订单处理成功');
            }catch (\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        } catch (\Throwable $exception) {
            Log::channel('recharge_callback')->info('节点订单处理失败'.$exception->getMessage());
        }
    }

    /**
     * 激活矿机
     * @param $user
     * @param $order
     * @param $data
     * @return void
     */
    public function activateMachineHandle($user,$order,$data): void
    {
        Log::channel('recharge_callback')->info('开始处理激活矿机订单');
        try {
            $extend = json_decode($order['extend'],true);
            $machine = UserMachine::query()
                ->where('id', $extend['machine_id'])
                ->first();
            if (empty($machine)){
                throw new \Exception("未找到需要激活的矿机");
            }
            DB::beginTransaction();
            try {
                $order->status = 2;
                $order->hash = $data['hash'];
                $order->save();

                RechargeModel::query()->create([
                    'user_id' => $user->id,
                    'order_no' => $order->order_no,
                    'type' => 3,
                    'nums' => $data['amount'],
                    'other_nums' => empty($data['amount1']) ? 0 : $data['amount1'],
                    'coin' => $data['coin_token'],
                    'other_coin' => empty($data['contract_address1']) ? '' : strtoupper(Currency::query()->where('contract_address',$data['contract_address1'])->value('name')),
                    'hash' => $data['hash'],
                    'detail' => json_encode($data,true)
                ]);

                WebsiteAnalyze::addData(WebsiteAnalyzeEnum::DESTORY_VOLUME,$data['amount']);

                //支付成功的操作
                PaySuccessService::getService()->buyActivaMachineSuccess($extend['active_price'],$machine,$data['amount']);

                DB::commit();
                Log::channel('recharge_callback')->info('矿机激活订单处理成功');
            }catch (\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        } catch (\Throwable $exception) {
            Log::channel('recharge_callback')->info('矿机激活订单处理失败'.$exception->getMessage());
        }
    }
}
