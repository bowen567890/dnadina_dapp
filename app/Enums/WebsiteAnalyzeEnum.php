<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class WebsiteAnalyzeEnum extends Enum
{

    const  string REGISTER_NUM = 'register_num'; //注册人数

    const string BACKEND_RECHARGE_USDT = 'backend_recharge_usdt'; //充值USDT

    const string BACKEND_RECHARGE_COIN = 'backend_recharge_coin'; //充值代币

    const string RECHARGE_USDT_NUM = 'recharge_usdt_num'; //今日入金USDT量

    const string RECHARGE_USDT_COUNT = 'recharge_usdt_count'; //今日入金USDT笔数

    const string RECHARGE_COIN_NUM = 'recharge_coin_num'; //今日入金代币量

    const string RECHARGE_COIN_COUNT ='recharge_coin_count'; //今日入金代币笔数

    const string RECHARGE_SPA_NUM = 'recharge_spa_num';//今日入金SPA数量

    const string WITHDRAW_NUM = 'withdraw_num'; //今日提现数量

    const string WITHDRAW_COUNT = 'withdraw_count'; //今日提现笔数

    const string WITHDRAW_FEE = 'withdraw_fee'; //今日提现手续费

    const string POWER_INCOME = 'power_income';//算力收益

    const string EQUIPMENT_INCOME = 'equipment_income'; //设备收益

    const string NODE_INCOME = 'node_income';

    const string NODE_WITHDRAW_INCOME = 'node_withdraw_income';
    const string CIRCULATION_VOLUME = 'circulation_volume';   //流通量
    const string DESTORY_VOLUME = 'destroy_volume';       //销毁两

}
