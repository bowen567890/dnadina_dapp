<?php

namespace App\Services\Recharge;

use App\Admin\Repositories\UsersMachine;
use App\Enums\IncomeTypeEnum;
use App\Enums\UserCoinAmountTypeEnum;
use App\Models\NodeLog;
use App\Models\UserMachine;
use App\Models\UsersPower;
use App\Services\BaseService;
use App\Services\User\BalanceService;

class PaySuccessService extends BaseService
{


    /**
     * 购买节点以后成功的操作
     * @param $user
     * @param $node
     * @return void
     */
    public function buyNodeSuccess($user,$node,$hash)
    {
        //设置为有效用户
        $user->valid_status = 1;
        if ($user->node_id < $node->id){
            $user->node_id = $node->id;
        }
        $user->save();

        NodeLog::query()->create([
            'user_id' => $user->id,
            'node_id' => $node->id,
            'price' => $node->price,
            'hash' => $hash,
        ]);

        //增加节点算力
        if ($node->gift_power > 0){
            UsersPower::insertLog($user->id,1,$node->gift_power);
        }
    }


    /**
     * 购买矿机以后的操作
     * @param $user
     * @param $num
     * @param $totalAmount
     * @return array
     */
    public function buyMachineSuccess($user,$num,$totalAmount)
    {
        $user->valid_status = 1;
        $user->save();

        //增加矿机算力
        UsersPower::insertLog($user->id,2,$num);

        //增加矿机记录
        $userMachine = UserMachine::query()->create([
            'user_id' => $user->id,
            'num' => $num,
            'total_amount' => $totalAmount,
            'is_settlement' => 1
        ]);

        return [
            'machine_id' => $userMachine->id,
        ];
    }

    /**
     * 激活矿机的操作
     * @param $user
     * @param $num
     * @param $totalAmount
     * @return array
     */
    public function buyActivaMachineSuccess($activePrice,$machine,$totalAmount)
    {
        $machine->status = 3;
        $machine->is_active = 1;
        $machine->active_price = $activePrice;
        $machine->active_pay = $totalAmount;
        $machine->active_time = date('Y-m-d H:i:s');
        $machine->save();
        return [
            'machine_id' => $machine->id,
        ];
    }
}
