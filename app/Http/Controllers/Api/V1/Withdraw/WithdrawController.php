<?php

namespace App\Http\Controllers\Api\V1\Withdraw;

use App\Enums\IncomeTypeEnum;
use App\Enums\QueueEnum;
use App\Enums\SystemEnum;
use App\Http\Controllers\Api\ApiController;
use App\Http\Request\Withdraw\WithdrawListRequest;
use App\Http\Request\Withdraw\WithdrawRequest;
use App\Jobs\WithdrawJob;
use App\Models\Withdraw;
use App\Services\User\BalanceService;
use App\Util\RedisLock;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\MyRedis;
use App\Models\MainCurrency;
use App\Models\User;
use App\Models\OrderLog;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\WebsiteAnalyzeDaily;
use App\Models\WebsiteStatistic;
use Illuminate\Support\Facades\Log;


class WithdrawController extends ApiController
{

    /**
     * 提现
     * @param WithdrawRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        try
        {
            throw new \Exception(Lang('敬请期待'));
            $on_chain_pay = config('env.ON_CHAIN_PAY');
            if (!$on_chain_pay) {
                throw new \Exception(Lang('敬请期待'));
            }
            
            $fStatus = intval(config('withdraw_status'));
            if ($fStatus!=1) {
                throw new \Exception(Lang('敬请期待'));
            }
            
            $user = $this->user();
            
            if ($user->can_withdraw!=1) {
                throw new \Exception(Lang('敬请期待'));
            }
            
            $in = $request->input();
            
            // 处理科学计数法问题，确保数字格式正确
            // 线上环境可能将小数值（如0.00006）转换为科学计数法（6.0E-5）
            $num_input = $in['num'] ?? '';
            
            // 验证输入是否为有效数字
            if (!is_numeric($num_input)) {
                throw new \Exception(Lang('请输入提现金额'), 400);
            }
            
            // 如果是浮点数或包含科学计数法，转换为标准数字格式
            if (is_float($num_input) || strpos(strtoupper((string)$num_input), 'E') !== false) {
                // 使用 sprintf 确保转换为标准数字格式，避免科学计数法
                $num_input = rtrim(rtrim(sprintf('%.10f', (float)$num_input), '0'), '.');
            }
            
            $num = @bcadd($num_input, '0', 10);
            if (bccomp($num, '0', 10)<=0) {
                throw new \Exception(Lang('请输入提现金额'), 400);
            }
            if (isset($in['coin_type']) && is_numeric($in['coin_type']) && in_array($in['coin_type'], [1,3])) {
                $in['coin_type'] = intval($in['coin_type']);
            } else {
                $in['coin_type'] = 1;
            }
            
            if ($in['coin_type']==3) {
                //             return responseValidateError(__('error.敬请期待'));
            }
            
            $lockKey = 'user:info:'.$user->id;
            $MyRedis = new MyRedis();
//                         $MyRedis->del_lock($lockKey);
            $lock = $MyRedis->setnx_lock($lockKey, 20);
            if(!$lock){
                throw new \Exception(Lang('操作频繁'), 400);
            }
            
            $withdraw_coin = 'withdraw_usdt';
            
            $coinPrice = '1';
            $coin_actual = 1;
            if ($in['coin_type']==1) 
            {
                $coin_type = 'usdt';
                $coinToken = 'USDT';
                $contractAddress = MainCurrency::query()->where('id', 1)->value('contract_address');
//                 $contractAddress = '0x1000000000000000000000000000000000000000';
                $withdrawFee = @bcadd(config('withdraw_fee_rate'), '0', 6);
                $withdraw_coin = 'withdraw_usdt';
                $coinPrice = '1';
                $coin_actual = 1;
            } 
            else if ($in['coin_type']==3) 
            {
                $coin_type = 'btc';
                $coinToken = 'BTC';
                
                $usdtCurrency = MainCurrency::query()->where('id', 1)->first();
                $btcCurrency = MainCurrency::query()->where('id', 3)->first();
                
                $contractAddress = $usdtCurrency->contract_address;
                
                $withdrawFee = @bcadd(config('withdraw_fee_rate'), '0', 6);
                $withdraw_coin = 'withdraw_btc';
                $coinPrice = $btcCurrency->rate;
                $coin_actual = 1;
            } 
            else if ($in['coin_type']==4) 
            {
                $coin_type = 'rwa';
                $coinToken = 'RWA';
                
                $rwaCurrency = MainCurrency::query()->where('id', 4)->first();
                $contractAddress = $rwaCurrency->contract_address;
                
                $withdrawFee = @bcadd(config('withdraw_fee_rate'), '0', 6);
                $withdraw_coin = 'withdraw_rwa';
                $coinPrice = $rwaCurrency->rate;
                $coin_actual = 4;
            } 
            
            $user = User::query()->where('id', $user->id)->first(['id','address','usdt','btc','rwa']);
            if (bccomp($num, $user->$coin_type, 10)>0){
                $MyRedis->del_lock($lockKey);
                throw new \Exception(Lang('余额不足'), 400);
            }
            $min_withdraw = bcadd(config('min_withdraw_value'), '0', 0);
            if (bccomp($min_withdraw, '0', 0)>0) {
                if ($in['coin_type']==1) {
                    if (bccomp($num, $min_withdraw, 2)<0) {
                        $MyRedis->del_lock($lockKey);
                        throw new \Exception(Lang("单笔最低提现价值为:",[$min_withdraw]), 400);
                    }
                } else if ($in['coin_type']==3) {
                    $coinPrice = MainCurrency::query()->where('id', 3)->value('rate');
                    $coinValue = bcmul($num, $coinPrice, 2);
                    if (bccomp($coinValue, $min_withdraw, 2)<0) {
                        $MyRedis->del_lock($lockKey);
                        throw new \Exception(Lang("单笔最低提现价值为:",[$min_withdraw]), 400);
                    }
                }
            }
//             //特殊情况
//             $wNum = intval(config('daily_withdraw_num'));
//             if (Withdraw::query()->where('user_id',$user->id)->whereDate('created_at',date('Y-m-d'))->count()>=$wNum){
//                 $MyRedis->del_lock($lockKey);
// //                 $format = __('error.每日提币次数');
//                 throw new \Exception(Lang("每日提币次数为:",[$wNum]), 400);
//             }
            
            DB::beginTransaction();
            try
            {
                $wallet = $user->address;
                
                $fee_amount = bcmul($num, $withdrawFee, 10);
                $ac_amount = bcsub($num, $fee_amount, 10);
                
                //BTC需要转成USDT
                $usdt_num = bcmul($num, $coinPrice, 10);
                $fee_amount_usdt = bcmul($fee_amount, $coinPrice, 10);
                $ac_amount_usdt = bcsub($usdt_num, $fee_amount_usdt, 10);
                
                $orderNum = get_ordernum();
                $withdraw = new Withdraw();
                $withdraw->ordernum = $orderNum;
                $withdraw->user_id = $user->id;
                $withdraw->receive_address = $wallet;
                $withdraw->num = $num;
                $withdraw->fee = $withdrawFee;
                $withdraw->coin_type = $in['coin_type'];
                $withdraw->fee_amount = $fee_amount;
                $withdraw->ac_amount = bcsub($num, $withdraw->fee_amount, 10);
                $withdraw->usdt_num = $usdt_num;
                $withdraw->fee_amount_usdt = $fee_amount_usdt;
                $withdraw->ac_amount_usdt = $ac_amount_usdt;
                $withdraw->coin_price = $coinPrice;
                $withdraw->coin_actual = $coin_actual;
                $withdraw->save();
                
                $userModel = new User();
                //分类1系统增加2系统扣除3余额提币4提币驳回
                $userModel->handleUser($coin_type, $user->id, $num, 2, ['cate'=>3, 'msg'=>'提币扣除', 'ordernum'=>$orderNum]);
                
                $OrderLog = new OrderLog();
                $OrderLog->ordernum = $orderNum;
                $OrderLog->user_id = $user->id;
                $OrderLog->type = 3;    //订单类型1节点订单2合约订单3余额提币
                $OrderLog->save();
                
                if ($withdraw->ac_amount>0)
                {
                    if ($in['coin_type']==3) {
                        $ac_amount = $ac_amount_usdt;
                    }
                    $http = new Client();
                    $data = [
                        'address' => $wallet,
                        'amount' => $ac_amount,
                        'contract_address' => $contractAddress,
                        'notify_url' => config('env.APP_URL').'/api/v1/wallWithdrawCallback',
                        'remarks' => $orderNum
                    ];
                    $response = $http->post('http://127.0.0.1:9090/v1/bnb/withdraw',[
                        'form_params' => $data,
                        'timeout' => 10,
                        'verify' => false
                    ]);
                    $result = $response->getBody()->getContents();
                    if (!is_array($result)) {
                        $result = json_decode($result, true);
                    }
                    if (!is_array($result) || !$result || !isset($result['code']) || $result['code']!=200)
                    {
                        DB::rollBack();
                        //                     Log::channel('withdraw')->info('提交提币申请失败');
                        throw new \Exception(Lang("网络异常"), 400);
                    } else {
                        Log::channel('withdraw')->info('提交提币申请'.var_export($data, true).'---'.var_export($result, true));
                    }
                }
                
                $wup = [
                    $withdraw_coin => DB::raw("`{$withdraw_coin}`+{$num}")
                ];
                
                if ($in['coin_type']==3) {
                    $wup['withdraw_usdt'] = DB::raw("`withdraw_usdt`+{$usdt_num}");
                }
                
                $date = date('Y-m-d');
                WebsiteAnalyzeDaily::query()
                    ->where('date', $date)
                    ->update($wup);
                
                WebsiteStatistic::query()
                    ->where('id', 1)
                    ->update($wup);
                
                DB::commit();
                $MyRedis->del_lock($lockKey);
//                 return responseJson();
                return $this->response();
            }
            catch (\Exception $e)
            {
                DB::rollBack();
                $MyRedis->del_lock($lockKey);
                return $this->__responseError($e->getMessage(),$e->getCode());
            }
            
        } catch (\Exception $exception){
            return $this->__responseError($exception->getMessage(),$exception->getCode());
//             return $this->__responseError($e->getMessage().$e->getLine(), $e->getCode());
        }
    }


    /**
     * 提现记录
     * @param WithdrawListRequest $request
     * @return JsonResponse
     */
    public function list(WithdrawListRequest $request): JsonResponse
    {
        try {
            $user = $this->user();
            $data = $request->only(['page','page_size','amount_type','type','add_type']);
            $query = Withdraw::query()->where('user_id',$user->id)->orderByDesc('id');
            $total = $query->count();
            $list = $query->orderByDesc('id')->offset(($data['page'] - 1) * $data['page_size'])
                ->limit($data['page_size'])
                ->select('ordernum','coin_type','num','fee','fee_amount','ac_amount','status','finsh_time','created_at')
                ->get();
            return $this->response([
                'list' => $list,
                'total' => $total,
            ]);
        }catch (\Exception $exception){
            return $this->__responseError($exception->getMessage(),$exception->getCode());
        }
    }
    
    /**
     * 提现
     * @param WithdrawRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function indexback2222(WithdrawRequest $request){
        try {
            $user = $this->user();
            $lock = Cache::lock('withdraw:index:'.$user->id,10);
            // 获取锁，如果获取失败则直接返回错误
            if (!$lock->get()) {
                throw new \Exception(Lang('操作频繁'), 400);
            }
            try {
                $data = $request->only(['amount','coin_id']);
                $userLimit = UsersLimitModel::query()->where('user_id',$user->id)->first();
                if ($data['coin_id'] == 1){
                    if (!empty($userLimit) && $userLimit->withdraw_usdt_status==1){
                        throw new \Exception(Lang('暂不可提现'),400);
                    }
                    $usdt_open_withdraw = Setting('usdt_withdraw_enable');
                    if (!$usdt_open_withdraw){
                        throw new \Exception(Lang('暂不可提现'),400);
                    }
                    $withdraw_rate = Setting('usdt_withdraw_rate')/100;
                    $min_withdraw = Setting('usdt_min_withdraw');
                    $max_withdraw = Setting('usdt_max_withdraw');
                    $daily_max_withdraw = Setting('usdt_daily_max_withdraw');
                }else{
                    if (!empty($userLimit) && $userLimit->withdraw_nadi_status==1){
                        throw new \Exception(Lang('暂不可提现'),400);
                    }
                    $fac_open_withdraw = Setting('fac_withdraw_enable');
                    if (!$fac_open_withdraw){
                        throw new \Exception(Lang('暂不可提现'),400);
                    }
                    $withdraw_rate = Setting('fac_withdraw_rate')/100;
                    $min_withdraw = Setting('fac_min_withdraw');
                    $max_withdraw = Setting('fac_max_withdraw');
                    $daily_max_withdraw = Setting('fac_daily_max_withdraw');
                }
                if ($min_withdraw > 0 && $data['amount'] < $min_withdraw){
                    throw new \Exception(Lang("单笔最低提现金额为:",[$min_withdraw]), 400);
                }
                if ($max_withdraw > 0 && $data['amount'] > $max_withdraw){
                    throw new \Exception(Lang("单笔最高提现金额为:",[$max_withdraw]), 400);
                }
                //判断是否超过每日提现金额
                if ($daily_max_withdraw > 0 ){
                    $hasWithdraw = Withdraw::query()->where('user_id',$user->id)->where('coin_id',$data['coin_id'])->whereIn('status',[0,1,2])->whereDate('created_at',date('Y-m-d'))->sum('num');
                    if (bcadd($data['amount'],$hasWithdraw,SystemEnum::DECIMAL) > $daily_max_withdraw){
                        throw new \Exception(Lang("单日最高提现:",[$daily_max_withdraw]), 400);
                    }
                }
                //判断余额是否存在
                $balance = BalanceService::getService()->getBalance($user->id,$data['coin_id']);
                if ($balance < $data['amount']){
                    throw new \Exception(Lang("余额不足"), 400);
                }
                $feeNum = bcmul($data['amount'],$withdraw_rate,6);
                $aAmount = bcsub($data['amount'],$feeNum,6);
                //燃料余额
                $withdrawId = 0;
                DB::transaction(function () use (&$withdrawId,$user,$data,$feeNum,$aAmount){
                    if ($data['coin_id'] == 1){
                        $pRate = Setting('usdt_withdraw_rate');
                    }else{
                        $pRate = Setting('fac_withdraw_rate');
                    }
                    $withdraw = Withdraw::query()->create([
                        'no' =>    'W' . date('YmdHis') . mt_rand(1000000, 99999000),
                        'coin_id' => $data['coin_id'],
                        'user_id' => $user->id,
                        'num' => $data['amount'],
                        'fee' => $pRate,
                        'status' => 1,
                        'fee_amount' => $feeNum,
                        'ac_amount' => $aAmount,
                    ]);
                    BalanceService::getService()->subIncome($user->id,$data['coin_id'],'-'.$data['amount'],IncomeTypeEnum::WITHDRAWAL, '提现');
                    $withdrawId = $withdraw->id;
                });
                    if ($withdrawId && app()->environment('production')){
                        WithdrawJob::dispatch($withdrawId)->onQueue(QueueEnum::Withdraw);
                    }
                    return $this->response();
            }catch (\Exception $e){
                throw new \Exception($e->getMessage(), 400);
            }finally{
                optional($lock)->release();
            }
        }catch (\Exception $exception){
            return $this->__responseError($exception->getMessage(),$exception->getCode());
        }
    }

}
