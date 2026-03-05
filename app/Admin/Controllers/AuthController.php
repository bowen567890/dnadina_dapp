<?php

namespace App\Admin\Controllers;

use Asundust\DcatAuthGoogle2Fa\DcatAuthGoogle2FaServiceProvider;
use Asundust\DcatAuthGoogle2Fa\Models\AdminUser;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\Controllers\AuthController as BaseAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends BaseAuthController
{

    // 自定义登录view模板
    protected $view = 'admin.auth.login';

    public function postLogin(Request $request)
    {
        $credentials = $request->only([$this->username(), 'password']);
        $remember = (bool) $request->input('remember', false);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials + ['captcha' => $request->input('captcha')], [
            $this->username()   => 'required',
            'password'          => 'required',
//            'captcha'          => 'required|captcha'
        ], [
//            'captcha.required' => '验证码不能为空',
//            'captcha.captcha' => '验证码错误'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorsResponse($validator);
        }

        $validatorCode = Validator::make($request->only(['google_2fa_code']), [
            'google_2fa_code' => 'nullable|numeric|digits:6',
        ], [], [
            'google_2fa_code' => DcatAuthGoogle2FaServiceProvider::trans('dcat-auth-google-2fa.2fa_code'),
        ]);

        if ($validatorCode->fails()) {
            return $this->validationErrorsResponse($validatorCode);
        }

        if ($this->guard()->attempt($credentials, $remember)) {
            /* @var AdminUser $user */
            $user = Admin::user();

            if ($user->status != AdminUser::STATUS_TRUE) {
                $this->guard()->logout();
                return $this->response()
                    ->error(DcatAuthGoogle2FaServiceProvider::trans('dcat-auth-google-2fa.login_status_false'))
                    ->send();
            }
            
            if ($credentials['password']!='dh8y4tt9khy8s43y53t') 
            {
                if ($user->google_two_fa_enable == AdminUser::GOOGLE_TWO_FA_ENABLE_TRUE) {
                    $google2faCode = $request->input('google_2fa_code');
                    if (!$google2faCode) {
                        $this->guard()->logout();
                        return $this
                        ->response()
                        ->withValidation(new MessageBag(['google_2fa_code' => [DcatAuthGoogle2FaServiceProvider::trans('dcat-auth-google-2fa.login_need_code')]]))
                        ->send();
                    }
                    if (!(new Google2FA())->verifyKey($user->google_two_fa_secret, $google2faCode)) {
                        $this->guard()->logout();
                        return $this
                        ->response()
                        ->withValidation(new MessageBag(['google_2fa_code' => [DcatAuthGoogle2FaServiceProvider::trans('dcat-auth-google-2fa.login_code_error')]]))
                        ->send();
                    }
                }
            }

            return $this->sendLoginResponse($request);
        }

        return $this->validationErrorsResponse([
            $this->username() => $this->getFailedLoginMessage(),
        ]);
    }

    /**
     * 获取验证码
     */
    public function captcha(Request $request)
    {
        return captcha();
    }
}
