<?php

namespace App\Jobs;

use App\Enums\QueueEnum;
use App\Enums\UserPerformanceTypeEnum;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateUserPerformanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $userId;

    private string $performanceType;

    private array $data;

    private bool $needUpdateLevel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId,$performanceType,$data = [],$needUpdateLevel = true)
    {
        $this->userId = $userId;
        $this->performanceType = $performanceType;
        $this->data = $data;
        $this->needUpdateLevel = $needUpdateLevel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::query()->find($this->userId);
        if (empty($user)) {
            return;
        }
        switch ($this->performanceType) {
            case UserPerformanceTypeEnum::BUY_NODE:
                $this->buyNodeHandle($user);
                break;
            case UserPerformanceTypeEnum::BUY_MACHINE:
                $this->buyMachineHandle($user);
                break;
        }


        //处理完业绩立马更新等级操作
        if ($this->needUpdateLevel) {
            $parentIds = [$user->id];
            if (!empty($user->path)){
                $parentIds = array_reverse(explode('-', trim($user->path, '-')));
                $parentIds = array_merge([$user->id],$parentIds);
            }
            UpdateUserLevelJob::dispatch($parentIds)->onQueue(QueueEnum::UserLevel);
        }
    }


    /**
     * 购买节点业绩处理逻辑
     * @param $user
     * @return void
     *  */
    private function buyNodeHandle($user): void
    {
        User::query()->where('id',$user->id)->update([
            'me_performance' => DB::raw('me_performance+'.$this->data['performance']),
            'total_performance' => DB::raw('total_performance+'.$this->data['performance']),
        ]);
        if (!empty($user->path)){
            $parentIds = array_reverse(explode('-', trim($user->path, '-')));
            User::query()->whereIn('id',$parentIds)->update([
                'team_performance' => DB::raw('team_performance+'.$this->data['performance']),
                'total_performance' => DB::raw('total_performance+'.$this->data['performance']),
            ]);
        }
    }




    /**
     * 购买矿机业绩处理逻辑(不增加用户的累计业绩)
     * @param $user
     */
    private function buyMachineHandle($user): void
    {
        User::query()->where('id',$user->id)->update([
            'me_performance' => DB::raw('me_performance+'.$this->data['performance']),
            'total_performance' => DB::raw('total_performance+'.$this->data['performance']),
        ]);
        if (!empty($user->path)){
            $parentIds = array_reverse(explode('-', trim($user->path, '-')));
            User::query()->whereIn('id',$parentIds)->update([
                'team_performance' => DB::raw('team_performance+'.$this->data['performance']),
                'total_performance' => DB::raw('total_performance+'.$this->data['performance']),
            ]);
        }
    }

}
