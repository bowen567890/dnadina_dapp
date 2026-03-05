<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class IncomeTypeEnum extends Enum implements LocalizedEnum
{

    #[Description('数据调整')]
    const int BACKEND_OPERATION = 1;

    #[Description('提现')]
    const int WITHDRAWAL = 2;

    #[Description('提现退回')]
    const int WITHDRAWAL_BACKEND = 3;

    #[Description('购买节点')]
    const int BUY_NODE = 4;

    #[Description('购买矿机')]
    const int BUY_MACHINE = 5;

    #[Description('直推奖励')]
    const int ZHI_INCOME = 6;

    #[Description('层级奖励')]
    const int LEVEL_INCOME = 7;

    #[Description('算力收益')]
    const int POWER_INCOME = 8;

    #[Description('团队奖励')]
    const int TEAM_INCOME = 9;

    #[Description('平级奖励')]
    const int PING_INCOME = 10;

    #[Description('手续费分润')]
    const int WITHDRAW_FEE_INCOME = 11;

    #[Description('激活矿机')]
    const int ACTIVATE_MACHINE = 12;
}
