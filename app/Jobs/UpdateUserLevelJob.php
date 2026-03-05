<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UsersLevel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateUserLevelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $userIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userIds)
    {
        $this->userIds = $userIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            //更新所有用户等级 - 按等级从高到低排序，优先匹配高等级
            $levelConfig = UsersLevel::query()
                ->orderByDesc('id') // 假设ID越大等级越高
                ->get()
                ->keyBy('id')
                ->toArray();

            // 按照用户层级深度排序，先处理下级用户（深度深的），再处理上级用户（深度浅的）
            $userList = User::query()
                ->whereIn('id', $this->userIds)
                ->orderByDesc('deep') // 按深度倒序，深度大的先处理
                ->orderByDesc('id')   // 相同深度的按ID倒序
                ->select(['id','me_performance','team_performance','level_id','backend_level_id'])
                ->get();

            // 预加载用户的直接子用户数据
            $userChildren = User::query()
                ->whereIn('parent_id', $this->userIds)
                ->select('id', 'parent_id')
                ->get()
                ->groupBy('parent_id')
                ->map(function($children) {
                    return $children->pluck('id')->toArray();
                })
                ->toArray();

            // 预先查询所有用户的团队有效人数，避免在循环中重复查询
            $teamValidCounts = [];
            if (!empty($this->userIds)) {
                foreach ($this->userIds as $userId) {
                    $teamValidCounts[$userId] = User::query()
                        ->where('path', 'like', "%-{$userId}-%")
                        ->where('valid_status', 1)
                        ->count();
                }
            }

            $this->processUsers($userList, $levelConfig, $userChildren, $teamValidCounts);
        });
    }

    /**
     * 处理用户等级更新逻辑
     */
    private function processUsers($userList, $levelConfig, $userChildren, $teamValidCounts)
    {
        // 逐个处理用户并立即更新，确保后续用户能获取到最新的等级数据
        foreach ($userList as $user){
            $newLevel = null;

            foreach ($levelConfig as $config){
                //判断如果当前等级小于等于后台设置的等级，直接推出
                if ($config['id'] <= $user->backend_level_id){
                    $newLevel = [
                        'level_id' => $config['id'],
                        'level_name' => $config['name']
                    ];
                    break;
                }else{
                    //个人算力检查
                    if ($config['me_power'] > 0){
                        if ($user->me_performance < $config['me_power']){
                            continue;
                        }
                    }
                    //团队算力检查
                    if ($config['team_power'] > 0){
                        if ($user->team_performance < $config['team_power']){
                            continue;
                        }
                    }
                    //团队有效人数检查
                    if ($config['team_valid_count'] > 0){
                        $count = $teamValidCounts[$user->id] ?? 0;
                        if ($count < $config['team_valid_count']){
                            continue;
                        }
                    }

                    if ($config['team_num'] > 0){
                        $count = 0;
                        $childrenIds = $userChildren[$user->id] ?? [];

                        // 动态查询最新的用户等级数据，确保获取到已更新的下级用户等级
                        if (!empty($childrenIds)) {
                            // 构建所有可能的path查询条件
                            $pathConditions = collect($childrenIds)->map(function($childId) {
                                return "%-{$childId}-%";
                            })->toArray();

                            // 实时查询：获取最新的用户等级数据，而不是使用预加载的数据
                            $qualifiedUsers = User::query()
                                ->where(function ($query) use ($childrenIds, $pathConditions) {
                                    $query->whereIn('id', $childrenIds);
                                    foreach ($pathConditions as $pathCondition) {
                                        $query->orWhere('path', 'like', $pathCondition);
                                    }
                                })
                                ->where('level_id', '>=', $config['team_level_id'])
                                ->get()
                                ->groupBy(function($user) use ($childrenIds) {
                                    // 确定这个用户属于哪个直接子用户的团队
                                    foreach ($childrenIds as $childId) {
                                        if ($user->id == $childId || strpos($user->path, "-{$childId}-") !== false) {
                                            return $childId;
                                        }
                                    }
                                    return null;
                                });

                            // 统计每个直接子用户团队中符合条件的用户数
                            foreach ($childrenIds as $childrenId) {
                                if (isset($qualifiedUsers[$childrenId]) && $qualifiedUsers[$childrenId]->count() >= 1) {
                                    $count++;
                                    if ($count >= $config['team_num']) {
                                        break;
                                    }
                                }
                            }
                        }
                        if ($count < $config['team_num']){
                            continue;
                        }
                    }
                    $newLevel = [
                        'level_id' => $config['id'],
                        'level_name' => $config['name']
                    ];
                    break;
                }
            }


            // 立即更新该用户的等级，确保下一个用户处理时能获取到最新数据
            if ($newLevel && ($user->level_id != $newLevel['level_id'])) {
                User::query()->where('id', $user->id)->update([
                    'level_id' => $newLevel['level_id'],
                    'level_name' => $newLevel['level_name']
                ]);
            }
        }
    }
}
