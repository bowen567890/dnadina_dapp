<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class QueueEnum extends Enum implements LocalizedEnum
{

    public const string UserLevel = 'psp/user/level'; //等级任务

    public const string Withdraw = 'psp/withdraw'; //用户提现操作

    public const string Settlement = 'psp/settlement'; //用户提现操作
}
