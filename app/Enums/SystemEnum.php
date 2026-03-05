<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class SystemEnum  extends Enum  implements LocalizedEnum
{
    /**
     * 系统金额小数位
     */
    public const int DECIMAL = 6;

}
