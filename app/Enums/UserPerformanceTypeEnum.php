<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class UserPerformanceTypeEnum  extends Enum
{

    #[Description('购买节点')]
    public const string BUY_NODE = 'buy_node';

    #[Description('销毁矿机')]
    public const string BUY_MACHINE = 'buy_machine';

}
