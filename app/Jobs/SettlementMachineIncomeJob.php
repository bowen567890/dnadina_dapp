<?php

namespace App\Jobs;


use App\Enums\IncomeTypeEnum;
use App\Enums\UserCoinAmountTypeEnum;
use App\Models\User;
use App\Models\UserMachine;
use App\Services\User\BalanceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SettlementMachineIncomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $machineId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($machineId)
    {
        $this->machineId = $machineId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $userMachine = UserMachine::query()->find($this->machineId);
        if (empty($userMachine) || $userMachine->is_settlement!=1) {
            return;
        }

        DB::beginTransaction();
        try {
            $user = User::query()->where('id',$userMachine->user_id)->first();

            if ($user->parent_id > 0){
                $parent = User::query()->find($user->parent_id);
                if ($parent->valid_status == 1){
                    $zhiRate = Setting('zhi_rate');

                    if ($zhiRate > 0){
                        $zhiRate = bcdiv($zhiRate,100,6);
                        $zhiIncome = bcmul($userMachine->total_amount,$zhiRate,6);
                        if ($zhiIncome > 0){
                            BalanceService::getService()->addIncome($user->id,UserCoinAmountTypeEnum::USDT,$zhiIncome,IncomeTypeEnum::ZHI_INCOME,'直推奖励');
                        }
                    }
                }
            }

            //层级奖励
            if (!empty($user->path)){
                $parentIds = array_reverse(explode('-',trim($user->path,'-')));

                //给直推奖励
                $cengRate = Setting('ceng_rate');
                if ($cengRate > 0){
                    $cengRate = bcdiv($cengRate,100,6);
                    $parentList = User::query()->whereIn('id',$parentIds)->orderByDesc('id')->get()->keyBy('id')->toArray();
                    //层级奖励
                    foreach ($parentIds as $key => $parentId){
                        $parent = $parentList[$parentId];
                        if (empty($parent) || $parent['valid_status'] != 1){
                            continue;
                        }

                        // 根据父级用户的有效直推人数确定能拿多少层级的奖励
                        $zhiNum = User::query()
                            ->where('parent_id', $parent['id'])
                            ->where('valid_status', 1)
                            ->count();
                        $maxLevel = 0;
                        if ($zhiNum >= 3) {
                            $maxLevel = 10; // 分享3个拿10代
                        } elseif ($zhiNum >= 2) {
                            $maxLevel = 6;  // 分享2个拿6代
                        } elseif ($zhiNum >= 1) {
                            $maxLevel = 3;  // 分享1个拿3代
                        }

                        // 如果当前层级超过了该用户能拿的最大层级，跳过
                        if ($key >= $maxLevel) {
                            continue;
                        }

                        // 计算层级奖励金额（拿上一代10%）
                        $levelIncome = bcmul($userMachine->total_amount, $cengRate, 6);

                        if ($levelIncome > 0) {
                            // 发放层级奖励
                            BalanceService::getService()->addIncome(
                                $parent['id'],
                                UserCoinAmountTypeEnum::USDT,
                                $levelIncome,
                                IncomeTypeEnum::LEVEL_INCOME,
                                '层级奖励-第'.($key + 1).'层'
                            );
                        }
                    }
                }
            }


            $userMachine->is_settlement = 2;
            $userMachine->save();

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
        }

    }
}
