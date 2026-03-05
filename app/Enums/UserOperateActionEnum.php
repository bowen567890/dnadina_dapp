<?php

namespace App\Enums;


use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class UserOperateActionEnum  extends Enum
{

    #[Description('登录')]
    public const string Login = 'login';

    #[Description('注册')]
    public const string Register = 'register';

}
