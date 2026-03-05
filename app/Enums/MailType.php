<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MailType extends Enum
{

    const string Register = "Register"; //注册邮件类型

    const string Forget = "Forget"; //找回密码邮件类型

    const string UpdateLoginPassword = "UpdateLoginPassword"; //修改登录密码

    const string UpdateTradePassword = "UpdateTradePassword";//修改交易密码

}
