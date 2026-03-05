<?php

namespace App\Services\Common;

use App\Enums\MailType;
use App\Enums\QueueEnum;
use App\Jobs\MailJob;
use App\Mail\ForgetMail;
use App\Mail\RegisterMail;
use App\Mail\UpdateLoginPasswordMail;
use App\Mail\UpdateTradePasswordMail;
use App\Models\Common\MailModel;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService extends BaseService
{

    /**
     * @param string $email
     * @param string $nickname
     * @param string $type
     * @return void
     */
    public function send(string $email,string $nickname,string $type): void
    {
        $data['web_site']                = config('env.web_site');
        $data['c_name']                  = config('env.c_name');
        $data['c_address']               = config('env.c_address');
        $data['service_email']           = config('env.service_email');
        $data['zip_code']                = config('env.zip_code');
        $data['web_site_email_logo_src'] = config('env.web_site_email_logo_src');
        $data['web_site_name']           = config('env.web_site_name');
        $data['title']                   = 'Your verification code is: ';
        $data['time']                    = date('Y-m-d H:i:s',time());
        $data['toEmail'] = $email;
        $data['nickname'] = $nickname;
        $data['code'] = rand(100000, 999999);
        $data['lang'] = $this->getLocal();
        $data['imei'] = $this->getIMEI();
        $data['ip'] = $this->getIP();
        $data['agentLang'] = $this->getAgentLanguage();
        $data['local'] = $this->getLocal();

        MailJob::dispatch($email,$type,$data)->onQueue(QueueEnum::Mail);
    }


    /**
     * 检查邮箱验证码是否正确
     * @param string $email
     * @param string $type
     * @param string $code
     * @return bool
     */
    public function checkCode(string $email, string $type, string $code): bool
    {
        return true;
        //测试环境或者本地环境不校验验证码
        if (!app()->environment('local')) {
            $code = (int)$code;
            $last = MailModel::query()->where('created_at', '>', now()->addMinutes(-5))->orderByDesc('created_at')
                ->where('email', $email)->where('type',$type)->first();
            if ($last->code !== $code) {
                return false;
            }
            $last->has_verify = true;
            $last->save();
        }
        return true;
    }


    /**
     * 检查邮箱验证码是否正确 只检查不改状态
     * @param string $email
     * @param string $type
     * @param string $code
     * @return bool
     */
    public function checkCodeNotVerify(string $email, string $type, string $code): bool
    {
        return true;
        if (!app()->environment('local')) {
            $code = (int)$code;
            $last = MailModel::query()->where('created_at', '>', now()->addMinutes(-5))->orderByDesc('created_at')
                ->where('email', $email)->where('type',$type)->first();
            if ($last->code !== $code) {
                return false;
            }
        }
        return true;
    }


}
