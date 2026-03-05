<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Enums\IncomeTypeEnum;
use App\Enums\WebsiteAnalyzeEnum;
use App\Http\Controllers\Api\ApiController;
use App\Models\RechargeModel;
use App\Models\WebsiteAnalyze;
use App\Models\Withdraw;
use App\Services\Recharge\RechargeService;
use App\Services\User\BalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MyRedis;
use App\Models\OrderLog;
use App\Models\User;
use App\Models\WebsiteAnalyzeDaily;
use App\Models\WebsiteStatistic;

class CallbackController extends ApiController
{

    /**
     * 钱包充值回调
     * @param Request $request
     * @return JsonResponse
     */
    public function wallRechargeCallback(Request $request): JsonResponse
    {
        Log::channel('recharge_callback')->info('收到回调',$request->post());
        Log::channel('recharge_callback')->info('请求IP:'.request()->ip());
        $data = $request->post();
        if (!$data || !isset($data['coin_token']))
        {
            Log::channel('recharge_callback')->info('无法继续11');
            return $this->responseError('参数不完整1');
        }
        RechargeService::getService()->walletRecharge($data);
        return $this->response();
    }

    public function wallWithdrawCallback(Request $request): void
    {
        Log::channel('withdraw_callback')->info('收到回调',$request->post());
        Log::channel('withdraw_callback')->info('请求IP:'.request()->ip());
        $in = $data = $request->post();
//         if (empty($data) || empty($data['address'])){
        if (!$data){
            Log::channel('withdraw_callback')->error('无法继续');
            exit();
        }
        $ordernum = $in['remarks'];
        
        $lockKey = 'callback:withdraw:'.$in['remarks'];
        $MyRedis = new MyRedis();
//                 $MyRedis->del_lock($lockKey);
        $lock = $MyRedis->setnx_lock($lockKey, 60);
        if(!$lock){
            Log::channel('withdraw_callback')->info('回调上锁失败', $in);
            echo '上锁失败';
            die;
        }
        
        $withdraw = Withdraw::query()
            ->where('ordernum', $in['remarks'])
            //             ->where('fee_status', 1)
            ->first();
        if (!$withdraw){
            Log::channel('withdraw_callback')->info('未找到数据无法继续');
            $MyRedis->del_lock($lockKey);
            exit();
        }
        if ($withdraw->status!=0){
            Log::channel('withdraw_callback')->info('数据已被处理，无需继续处理');
            $MyRedis->del_lock($lockKey);
            exit();
        }
        
        $userModel = new User();
        DB::beginTransaction();
        try
        {
            $hash = isset($in['hash']) && $in['hash'] ? $in['hash'] : '';
            if ($data['status']==5)
            {
                $withdraw->status = 1;
                $withdraw->finsh_time = date('Y-m-d H:i:s');
                $withdraw->hash = $hash;
                $withdraw->save();
                
                
                $withdraw_coin = 'withdraw_usdt';
                
                if ($withdraw->coin_type==1) {
                    $withdraw_coin = 'withdraw_usdt_issue';
                } else if ($withdraw->coin_type==3) {
                    $withdraw_coin = 'withdraw_btc_issue';
                } else if ($withdraw->coin_type==4) {
                    $withdraw_coin = 'withdraw_rwa_issue';
                } 
                
                $date = date('Y-m-d');
                $wup = [$withdraw_coin => DB::raw("`{$withdraw_coin}`+{$withdraw->ac_amount}")];
                if ($withdraw->coin_type==3) {
                    $wup['withdraw_usdt_issue'] = DB::raw("`withdraw_usdt_issue`+{$withdraw->ac_amount_usdt}");
                }
                
                WebsiteAnalyzeDaily::query()
                    ->where('date', $date)
                    ->update($wup);
                
                WebsiteStatistic::query()
                    ->where('id', 1)
                    ->update($wup);
                
                $this->setOrderStatus($ordernum, 1);
                DB::commit();
                $MyRedis->del_lock($lockKey);
            }
            else if ($data['status']==6)
            {
                if ($withdraw->coin_type==1) {
                    $table = 'usdt';
                } else if ($withdraw->coin_type==3) {
                    $table = 'btc';
                }
                //判断黑洞地址
                if ($withdraw->user_id>0) {
                    $userModel->handleUser($table, $withdraw->user_id, $withdraw->num, 1, ['cate'=>4, 'msg'=>'提币驳回', 'ordernum'=>$withdraw->ordernum]);
                }
                
                $withdraw->status = 2;
                $withdraw->finsh_time = date('Y-m-d H:i:s');
                $withdraw->hash = $hash;
                $withdraw->save();
                
                $this->setOrderStatus($ordernum, 1);
                DB::commit();
                $MyRedis->del_lock($lockKey);
            }
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $MyRedis->del_lock($lockKey);
            Log::channel('withdraw_callback')->info('提币回调异常');
        }
        
        $MyRedis->del_lock($lockKey);
        echo '提币成功';
    }
    
    /**
     * 修改订单状态
     */
    protected function setOrderStatus($ordernum, $status=1) {
        OrderLog::query()->where('ordernum', $ordernum)->update(['status'=>$status]);
    }
    

    public function wallWithdrawCallback2222(Request $request): void
    {
        Log::channel('withdraw_callback')->info('收到回调',$request->post());
        Log::channel('withdraw_callback')->info('请求IP:'.request()->ip());
        $data = $request->post();
        if (empty($data) || empty($data['address'])){
            Log::channel('withdraw_callback')->error('无法继续');
            exit();
        }
        $remarks = explode('@',$data['remarks']);
        if (isset($remarks[1])){
            if ($remarks[0] == '发起提现'){
                try {
                    DB::transaction(function () use ($data, $remarks) {
                        $withdraw = Withdraw::query()->where('id', $remarks[1])->sharedLock()->first();
                        if (empty($withdraw)) {
                            Log::channel('withdraw_callback')->error('未找到数据无法继续');
                            throw new \Exception('为找到数据');
                        }
                        if ($withdraw['status'] != 1) {
                            Log::channel('withdraw_callback')->error('数据已被处理，无需继续处理');
                            exit();
                        }
                        if ($data['status'] == 5) {
                            $withdraw->status = 2;
                            $withdraw->finsh_time = date('Y-m-d H:i:s');
                            $withdraw->save();

                            //增加提现统计
                            if ($withdraw->user_id > 0){
                                WebsiteAnalyze::addData(WebsiteAnalyzeEnum::WITHDRAW_NUM,$withdraw->ac_amount);
                                WebsiteAnalyze::addData(WebsiteAnalyzeEnum::WITHDRAW_FEE,$withdraw->fee_amount);
                                WebsiteAnalyze::addData(WebsiteAnalyzeEnum::WITHDRAW_COUNT,1);
                            }elseif ($withdraw->user_id == 0){
                                WebsiteAnalyze::addData(WebsiteAnalyzeEnum::DESTORY_VOLUME,$withdraw->num);
                            }
                        } elseif ($data['status'] == 6) {
                            $withdraw->status = 3;
                            $withdraw->finsh_time = date('Y-m-d H:i:s');
                            $withdraw->save();
                            $totalNum = $withdraw->num;
                            if ($withdraw->user_id > 0){
                                BalanceService::getService()->addIncome($withdraw->user_id, $withdraw->coin_id, $totalNum,
                                    IncomeTypeEnum::WITHDRAWAL_BACKEND, '提现退回');
                            }
                        }
                    });
                    Log::channel('withdraw_callback')->info('操作成功');
                } catch (\Throwable $e) {
                    Log::channel('withdraw_callback')->error('操作失败'.$e->getMessage());
                    exit();
                }
            }elseif ($remarks[0] == '打入黑洞'){
                try {
                    DB::transaction(function () use ($data, $remarks) {
                        $destroy = DestroyLog::query()->where('id', $remarks[1])->sharedLock()->first();
                        if (empty($destroy)) {
                            Log::channel('withdraw_callback')->error('未找到数据无法继续');
                            throw new \Exception('为找到数据');
                        }
                        if ($destroy['is_success'] != 1) {
                            Log::channel('withdraw_callback')->error('数据已被处理，无需继续处理');
                            exit();
                        }
                        if ($data['status'] == 5) {
                            $destroy->status = 2;
                            $destroy->hash = $data['hash'];
                            $destroy->save();
                        } elseif ($data['status'] == 6) {
                            $destroy->status = 3;
                            $destroy->save();
                        }
                    });
                    Log::channel('withdraw_callback')->info('操作成功');
                } catch (\Throwable $e) {
                    Log::channel('withdraw_callback')->error('操作失败'.$e->getMessage());
                    exit();
                }
            }
        }
    }
}
