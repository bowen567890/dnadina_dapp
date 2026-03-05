<?php

namespace App\Services\User;

use App\Enums\IncomeAmountTypeEnum;
use App\Enums\SystemEnum;
use App\Enums\UserCoinAmountTypeEnum;
use App\Models\IncomeLogModel;
use App\Models\User;
use App\Models\UsersCoinModel;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BalanceService extends BaseService
{


    /**
     * 增加余额
     */
    public function addIncome( $userId,$amountType,  $amount, $incomeType, $remark = ''): void
    {
        if ($amount < 0){
            throw new \Exception('金额错误');
        }
        $balanceModel = $this->getBalanceModel($userId,$amountType);
        $before = $balanceModel->amount;
        $after = bcadd(bcmul($balanceModel->amount,1,6),bcmul($amount,1,6),SystemEnum::DECIMAL);

        $this->addLog([
            'user_id' => $userId,
            'amount_type' => $amountType,
            'before' => $before,
            'total' => $amount,
            'after' => $after,
            'type' => $incomeType,
            'remark' => $remark,
            'add_type' => 1,
        ]);
        UsersCoinModel::query()->where('id',$balanceModel->id)->increment('amount',$amount);
    }

    /**
     * 减少余额
     * @throws \Exception
     */
    public function subIncome($userId,$amountType,$amount,$incomeType, $remark = ''): void
    {
        if ($amount > 0){
            throw new \Exception('金额错误');
        }
        $balanceModel = $this->getBalanceModel($userId,$amountType);
        $before = $balanceModel->amount;
        $after = bcsub($balanceModel->amount,abs($amount),SystemEnum::DECIMAL);
        $this->addLog([
            'user_id' => $userId,
            'amount_type' => $amountType,
            'before' => $before,
            'total' => $amount,
            'after' => $after,
            'type' => $incomeType,
            'remark' => $remark,
            'add_type' => 2,
        ]);
        UsersCoinModel::query()->where('id',$balanceModel->id)->decrement('amount',abs($amount));
    }


    /**
     * 锁定账户资产
     * @param $userId
     * @param $amountType
     * @param $amount
     * @return void
     * @throws \Exception
     */
    public function lockAmount($userId,$amountType,$amount): void
    {
        if ($amount < 0){
            throw new \Exception('金额错误');
        }
        $balanceModel = $this->getBalanceModel($userId,$amountType);
        if ($balanceModel->amount < $amount){
            throw new \Exception('余额不足');
        }
        UsersCoinModel::query()->where('id',$balanceModel->id)->increment('lock_amount',$amount);
    }


    /**
     * 解锁账户资产
     * @param $userId
     * @param $amountType
     * @param $amount
     * @return void
     * @throws \Exception
     */
    public function unlockAmount($userId,$amountType,$amount): void
    {
        if ($amount < 0){
            throw new \Exception('金额错误');
        }
        $balanceModel = $this->getBalanceModel($userId,$amountType);
//        if ($balanceModel->lock_amount < $amount){
//            throw new \Exception('余额不足');
//        }
        UsersCoinModel::query()->where('id',$balanceModel->id)->decrement('lock_amount',$amount);
    }


    /**
     * 增加日志
     * @param array $log
     * @return void
     */
    public function addLog(array $log = []): void
    {
//        IncomeLogModel::create($log);//主表一份
        IncomeLogModel::useShardingTable($log['user_id'])->create($log);//分表一份
    }


    /**
     * 获取余额模型
     * @param int $userId
     * @param int $amountType
     * @return Builder|Model
     */
    public function getBalanceModel(int $userId,int $amountType): Builder|Model
    {
        return UsersCoinModel::query()->firstOrCreate([
            'user_id' => $userId ,
            'type' => $amountType
        ],[
            'user_id' => $userId ,
            'type' => $amountType,
            'amount' => 0,
            'lock_amount' => 0,
        ]);
    }

    /**
     * 根据用户ID获取余额
     * @param int $userId
     * @param int $amountType
     * @return int|mixed
     */
    public function getBalance(int $userId,int $amountType): mixed
    {
        $balanceModel = UsersCoinModel::query()->where('user_id',$userId)->where('type',$amountType)->first();
        if (empty($balanceModel)){
            $balance = 0;
        }else{
            //如果有资金，需要判断累计资金-锁定资金
            $balance = bcsub($balanceModel->amount,$balanceModel->lock_amount,SystemEnum::DECIMAL);
        }
        return $balance;
    }

}
