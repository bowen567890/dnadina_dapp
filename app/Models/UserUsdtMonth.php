<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UserUsdtMonth extends Model
{
    // 禁用时间戳
    public $timestamps = false;
    
    // 动态选择表名
    public function getTable()
    {
        // 获取月份参数，如果没有输入则不使用默认值，而是使用主表
        $month = request()->input('month');
        
        // 如果指定了月份，使用月份表
        if (!$month) {
            $month = date('Y-m');
        } else {
            $table = $this->getTablePrefix() . $month;
            // 判断表是否存在，如果不存在，则使用主表
            if (!Schema::hasTable($table)) {
                $month = date('Y-m');
            }
        }
        return $this->getTablePrefix() . $month;
    }
    
    // 获取表前缀（可以自定义）
    protected function getTablePrefix()
    {
        return 'user_usdt';
    }
    
    // 自动创建表
    protected function createTable($table)
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