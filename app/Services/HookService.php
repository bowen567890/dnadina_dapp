<?php

namespace App\Services;

use App\Enums\WebsiteAnalyzeEnum;
use App\Models\User;
use App\Models\WebsiteAnalyze;
use App\Services\Common\GeoIPService;
use App\Services\User\BalanceService;
use Carbon\Carbon;
use App\Models\WebsiteStatistic;
use App\Models\WebsiteAnalyzeDaily;

class HookService extends BaseService
{

    /**
     *  登录后钩子
     * @param User $user
     * @return void
     */
    public function loginAfterHook(User $user): void
    {

    }

    /**
     * 注册后钩子
     * @param User $user
     * @return void
     */
    public function registerAfterHook(User $user): void
    {
        //查询上级是谁
        if (!empty($user->path)){
            $pUser = explode('-',trim($user->path,'-'));
            $pUserId = $pUser[count($pUser)-1];
            //给上级直推人数加1 ，以及整个链条上的所有人团队人数+1
            User::query()->where('id',$pUserId)->increment('zhi_num');
            User::query()->whereIn('id',$pUser)->increment('team_num');
        }
        
        $date = date('Y-m-d');
        WebsiteStatistic::query()->where('id', 1)->increment('register_num', 1);
        WebsiteAnalyzeDaily::query()->where('date', $date)->increment('register_num', 1);
        //统计注册人数
//         WebsiteAnalyze::addData(WebsiteAnalyzeEnum::REGISTER_NUM,1);
        
    }


}
