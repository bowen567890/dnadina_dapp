<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        //查询代币价格
//         $schedule->command('sync:tokenprice')->cron('* * * * *');
        
        
        //每月自动创建分表 每个月27,28号自动创建下一个月的表
//         $schedule->command('command:CreateMonthTable')->cron('30 1 27,28 * *');
        
        //每5秒执行一次
//         $schedule->command('command:SyncPowerEvent')->cron('* * * * *');
        
        //每天12点结算数据
//         $schedule->command('app:settlement-command')->cron('2,12,22,32,42,52 * * * *');

        //节点加权奖励
//         $schedule->command('command:NodeDivvyReward')->cron('0 0 * * *');
        
        //创建明日数据统计
        $schedule->command('command:CreateTomorrowData')->cron('0 */12 * * *');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
