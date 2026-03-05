<?php

namespace App\Enums;


use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class IncomeAmountTypeEnum  extends Enum
{

    #[Description('USDT余额')]
    public const int USDT = 1;

    #[Description('FAC余额')]
    public const int FAC = 2;


}
