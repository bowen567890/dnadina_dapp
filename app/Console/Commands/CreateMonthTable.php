<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\MyRedis;

class CreateMonthTable extends Command
{
    protected $signature = 'command:CreateMonthTable';

    protected $description = '每月自动创新表';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $lockKey = 'command:CreateMonthTable';
        $MyRedis = new MyRedis();
//                 $MyRedis->del_lock($lockKey);
        $lock = $MyRedis->setnx_lock($lockKey, 300);
        if ($lock)
        {
            $time = time();
            $time2 = time()+86400*15;
            
            $this->UserBtcTable($time);
            $this->UserUsdtTable($time);
            $this->UserRwaTable($time);
            $this->UserMinePowerTable($time);
            $this->UserCastPowerTable($time);
            
            $this->UserBtcTable($time2);
            $this->UserUsdtTable($time2);
            $this->UserRwaTable($time2);
            $this->UserMinePowerTable($time2);
            $this->UserCastPowerTable($time2);
            
            $MyRedis = new MyRedis();
            $MyRedis->del_lock($lockKey);
        }
    }
    
    // 自动创建表
    protected function UserBtcTable($time)
    {
        $currentMonth = date('Y-m', $time);
        $table = 'user_btc'.$currentMonth;
        
        if (!Schema::hasTable($table)) 
        {
            $createSql = "CREATE TABLE `{$table}` (
              `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
              `user_id` int NOT NULL DEFAULT '0' COMMENT '用户ID',
              `from_user_id` int NOT NULL DEFAULT '0' COMMENT '来源用户ID',
              `type` tinyint NOT NULL DEFAULT '0' COMMENT '类型',
              `cate` tinyint NOT NULL DEFAULT '0' COMMENT '分类1系统增加2系统扣除3提币扣除4提币驳回5合约挖矿产出6推荐奖励7等级奖励8节点挖矿产出',
              `total` decimal(25,10) NOT NULL DEFAULT '0.0000000000' COMMENT '金额',
              `msg` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '备注',
              `content` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '详细内容',
              `ordernum` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '订单号',
              `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
              `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
              PRIMARY KEY (`id`) USING BTREE,
              KEY `user_id` (`user_id`) USING BTREE,
              KEY `type` (`type`) USING BTREE,
              KEY `cate` (`cate`) USING BTREE,
              KEY `from_user_id` (`from_user_id`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='BTC日志';";
            
            DB::statement($createSql);
        }
    }
    
    // 自动创建表
    protected function UserMinePowerTable($time)
    {
        $currentMonth = date('Y-m', $time);
        $table = 'user_mine_power'.$currentMonth;
        
        if (!Schema::hasTable($table))
        {
            $createSql = "CREATE TABLE `{$table}` (
              `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
              `user_id` int NOT NULL DEFAULT '0' COMMENT '用户ID',
              `from_user_id` int NOT NULL DEFAULT '0' COMMENT '来源用户ID',
              `type` tinyint NOT NULL DEFAULT '0' COMMENT '类型',
              `cate` tinyint NOT NULL DEFAULT '0' COMMENT '分类1系统增加2系统扣除3购买节点4购买合约5合约挖矿扣除6节点挖矿扣除',
              `total` decimal(25,6) NOT NULL DEFAULT '0.000000' COMMENT '金额',
              `msg` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '备注',
              `content` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '详细内容',
              `ordernum` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '订单号',
              `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
              `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
              PRIMARY KEY (`id`) USING BTREE,
              KEY `user_id` (`user_id`) USING BTREE,
              KEY `type` (`type`) USING BTREE,
              KEY `cate` (`cate`) USING BTREE,
              KEY `from_user_id` (`from_user_id`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='挖矿算力日志';";
            
            DB::statement($createSql);
        }
    }
    
    // 自动创建表
    protected function UserCastPowerTable($time)
    {
        $currentMonth = date('Y-m', $time);
        $table = 'user_cast_power'.$currentMonth;
        
        if (!Schema::hasTable($table))
        {
            $createSql = "CREATE TABLE `{$table}` (
              `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
              `user_id` int NOT NULL DEFAULT '0' COMMENT '用户ID',
              `from_user_id` int NOT NULL DEFAULT '0' COMMENT '来源用户ID',
              `type` tinyint NOT NULL DEFAULT '0' COMMENT '类型',
              `cate` tinyint NOT NULL DEFAULT '0' COMMENT '分类1系统增加2系统扣除3购买节点4购买合约',
              `total` decimal(25,6) NOT NULL DEFAULT '0.000000' COMMENT '金额',
              `msg` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '备注',
              `content` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '详细内容',
              `ordernum` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '订单号',
              `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
              `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
              PRIMARY KEY (`id`) USING BTREE,
              KEY `user_id` (`user_id`) USING BTREE,
              KEY `type` (`type`) USING BTREE,
              KEY `cate` (`cate`) USING BTREE,
              KEY `from_user_id` (`from_user_id`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='铸币算力日志';";
            
            DB::statement($createSql);
        }
    }
    
    // 自动创建表
    protected function UserUsdtTable($time)
    {
        $currentMonth = date('Y-m', $time);
        $table = 'user_usdt'.$currentMonth;
        
        if (!Schema::hasTable($table))
        {
            $createSql = "CREATE TABLE `{$table}` (
              `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
              `user_id` int NOT NULL DEFAULT '0' COMMENT '用户ID',
              `from_user_id` int NOT NULL DEFAULT '0' COMMENT '来源用户ID',
              `type` tinyint NOT NULL DEFAULT '0' COMMENT '类型',
              `cate` tinyint NOT NULL DEFAULT '0' COMMENT '分类1系统增加2系统扣除3提币扣除4提币驳回5挖矿产出6推荐奖励7等级奖励',
              `total` decimal(25,8) NOT NULL DEFAULT '0.00000000' COMMENT '金额',
              `msg` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '备注',
              `content` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '详细内容',
              `ordernum` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '订单号',
              `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
              `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
              PRIMARY KEY (`id`) USING BTREE,
              KEY `user_id` (`user_id`) USING BTREE,
              KEY `type` (`type`) USING BTREE,
              KEY `cate` (`cate`) USING BTREE,
              KEY `from_user_id` (`from_user_id`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='USDT日志';";
            
            DB::statement($createSql);
        }
    }
    
    // 自动创建表
    protected function UserRwaTable($time)
    {
        $currentMonth = date('Y-m', $time);
        $table = 'user_rwa'.$currentMonth;
        
        if (!Schema::hasTable($table))
        {
            $createSql = "CREATE TABLE `{$table}` (
              `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
              `user_id` int NOT NULL DEFAULT '0' COMMENT '用户ID',
              `from_user_id` int NOT NULL DEFAULT '0' COMMENT '来源用户ID',
              `type` tinyint NOT NULL DEFAULT '0' COMMENT '类型',
              `cate` tinyint NOT NULL DEFAULT '0' COMMENT '分类1系统增加2系统扣除3提币扣除4提币驳回5铸币产出',
              `total` decimal(25,10) NOT NULL DEFAULT '0.0000000000' COMMENT '金额',
              `msg` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '备注',
              `content` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '详细内容',
              `ordernum` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '订单号',
              `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
              `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
              PRIMARY KEY (`id`) USING BTREE,
              KEY `user_id` (`user_id`) USING BTREE,
              KEY `type` (`type`) USING BTREE,
              KEY `cate` (`cate`) USING BTREE,
              KEY `from_user_id` (`from_user_id`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='RWA日志';";
            
            DB::statement($createSql);
        }
    }
}
