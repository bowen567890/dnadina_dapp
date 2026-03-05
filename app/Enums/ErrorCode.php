<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ErrorCode extends Enum
{

    public const int NOT_SET_TRADE_PASSWORD = 100001;//未设置交易密码

    public const int NOT_SET_GOOGLE2_FA = 100002;//未设置谷歌秘钥

}
