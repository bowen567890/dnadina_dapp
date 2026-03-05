<style>
    .row {
        margin: 0;
    }
    .col-md-12,
    .col-md-3 {
        padding: 0;
    }
    @media screen and (min-width: 1000px) and (max-width: 1150px) {
        .col-lg-3,
        .col-lg-9 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    @media screen and (min-width: 1151px) and (max-width: 1300px) {
        .col-lg-3 {
            flex: 0 0 45%;  /* 从 40% 改为 45% */
            max-width: 45%;
        }
        .col-lg-9 {
            flex: 0 0 55%;  /* 从 60% 改为 55% */
            max-width: 55%;
        }
    }
    @media screen and (min-width: 1301px) and (max-width: 1700px) {
        .col-lg-3 {
            flex: 0 0 40%;  /* 从 35% 改为 40% */
            max-width: 40%;
        }
        .col-lg-9 {
            flex: 0 0 60%;  /* 从 65% 改为 60% */
            max-width: 60%;
        }
    }
    @media screen and (min-width: 1301px) and (max-width: 1700px) {
        .col-lg-3 {
            flex: 0 0 35%;
            max-width: 35%;
        }
        .col-lg-9 {
            flex: 0 0 65%;
            max-width: 65%;
        }
    }

    .login-page {
        height: auto;
    }
    .login-main {
        position: relative;
        display: flex;
        min-height: 100vh;
        flex-direction: row;
        align-items: stretch;
        margin: 0;
    }

    .login-main .login-page {
        background-color: #fff;
    }

    .login-main .card {
        box-shadow: none;
    }

    .login-main .auth-brand {
        margin: 4rem 0 4rem;
        font-size: 28px;  /* 从 26px 改为 28px */
        width: 380px;     /* 从 325px 改为 380px */
    }

    @media (max-width: 576px) {
        .login-main .auth-brand {
            width: 90%;
            margin-left: 24px
        }
    }

    .login-main .login-logo {
        font-size: 2.3rem;  /* 从 2.1rem 改为 2.3rem */
        font-weight: 300;
        margin-bottom: 1rem;  /* 从 0.9rem 改为 1rem */
        text-align: left;
        margin-left: 20px;
    }

    .login-main .login-box-msg {
        margin: 0;
        padding: 0 0 25px;    /* 从 20px 改为 25px */
        font-size: 1rem;      /* 从 0.9rem 改为 1rem */
        font-weight: 400;
        text-align: left;
    }

    .login-main .btn {
        width: 100%;
    }

    .login-page-right {
        padding: 6rem 3rem;
        flex: 1;
        position: relative;
        color: #fff;
        background-color: rgba(0, 0, 0, 0.3);
        text-align: center !important;
        background-size: cover;
    }

    .login-description {
        position: absolute;
        margin: 0 auto;
        padding: 0 1.75rem;
        bottom: 3rem;
        left: 0;
        right: 0;
    }

    .content-front {
        position: absolute;
        left: 0;
        right: 0;
        height: 100vh;
        background: rgba(0,0,0,.1);
        margin-top: -6rem;
    }

    body.dark-mode .content-front {
        background: rgba(0,0,0,.3);
    }

    body.dark-mode .auth-brand {
        color: #cacbd6
    }

    .form-label-group {
        margin-bottom: 3rem;  /* 从 2.5rem 增加到 3rem */
    }

    .form-control {
        height: calc(3em + 0.75rem + 2px);  /* 从 2.5em 增加到 3em */
        font-size: 1.2rem;    /* 从 1.1rem 增加到 1.2rem */
        padding: 1.2rem 1rem 0.2rem;  /* 添加内边距 */
    }

    .form-control-position {
        top: 12px;  /* 从 8px 调整到 12px，以适应更大的输入框 */
    }

    .form-label-group > label {
        padding-top: 1rem;  /* 从 0.85rem 增加到 1rem */
        font-size: 1.2rem;  /* 从 1.1rem 增加到 1.2rem */
    }

    .login-main .card-body {
        padding: 3rem 2rem;   /* 从 2rem 1.5rem 增加到 3rem 2rem */
    }

    .form-control {
        height: calc(2.5em + 0.75rem + 2px);  /* 增加输入框高度 */
        font-size: 1.1rem;    /* 增加字体大小 */
    }

    .form-control-position {
        top: 8px;  /* 调整图标位置以适应更大的输入框 */
    }

    .form-label-group > label {
        padding-top: 0.85rem;  /* 调整标签位置 */
        font-size: 1.1rem;     /* 增加标签字体大小 */
    }

    .login-main .card-body {
        padding: 2rem 1.5rem;   /* 增加表单区域的内边距 */
    }
</style>

<div class="row login-main">
    <div class="col-lg-9 col-12 login-page-right" @if(config('admin.login_background_image'))style="background: url({{admin_asset(config('admin.login_background_image'))}}) center no-repeat;background-size: cover;"@endif>
        <div class="content-front"></div>
    </div>
    <div class="col-lg-3 col-12 bg-white">
        <div class="login-page">
            <div class="auth-brand text-lg-left">
                {!! config('admin.logo') !!}
            </div>

            <div class="login-box">
                <div class="login-logo mb-2">
                    <h4 class="mt-0">管理后台</h4>
                    <p class="login-box-msg mt-1 mb-1">{{ __('admin.welcome_back') }}</p>
                </div>
                <div class="card card-primary card-outline card-outline-tabs" style="box-shadow:0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.2)">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">

                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                <form id="login-form" method="POST" action="{{ admin_url('auth/login') }}">

                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                                        <input
                                            type="text"
                                            class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                            name="username"
                                            placeholder="{{ trans('admin.username') }}"
                                            value="admin"
                                            required
                                            autofocus
                                        >

                                        <div class="form-control-position">
                                            <i class="feather icon-user"></i>
                                        </div>

                                        <label for="email">{{ trans('admin.username') }}</label>

                                        <div class="help-block with-errors"></div>
                                        @if($errors->has('username'))
                                            <span class="invalid-feedback text-danger" role="alert">
                                                    @foreach($errors->get('username') as $message)
                                                    <span class="control-label" for="inputError"><i class="feather icon-x-circle"></i> {{$message}}</span><br>
                                                @endforeach
                                                </span>
                                        @endif
                                    </fieldset>

                                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                                        <input
                                            minlength="5"
                                            maxlength="20"
                                            id="password"
                                            type="password"
                                            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                            name="password"
                                            placeholder="{{ trans('admin.password') }}"
                                            required
                                            value="admin"
                                            autocomplete="current-password"
                                        >

                                        <div class="form-control-position">
                                            <i class="feather icon-lock"></i>
                                        </div>
                                        <label for="password">{{ trans('admin.password') }}</label>

                                        <div class="help-block with-errors"></div>
                                        @if($errors->has('password'))
                                            <span class="invalid-feedback text-danger" role="alert">
                                                @foreach($errors->get('password') as $message)
                                                    <span class="control-label" for="inputError"><i class="feather icon-x-circle"></i> {{$message}}</span><br>
                                                @endforeach
                                                    </span>
                                        @endif

                                    </fieldset>
{{--                                    <fieldset class="form-label-group form-group position-relative has-icon-left">--}}
{{--                                        <div class="d-flex align-items-center" style="width: 100%;">--}}
{{--                                            <!-- 输入框 -->--}}
{{--                                            <input--}}
{{--                                                type="text"--}}
{{--                                                class="form-control {{ $errors->has('captcha') ? 'is-invalid' : '' }}"--}}
{{--                                                name="captcha"--}}
{{--                                                placeholder="验证码"--}}
{{--                                                required--}}
{{--                                                style="flex: 1; height: 45px; min-width: 8rem; margin-right: 10px;"--}}
{{--                                            >--}}
{{--                                            <div class="form-control-position">--}}
{{--                                                <i class="feather icon-shield"></i>--}}
{{--                                            </div>--}}
{{--                                            <!-- 验证码图片 -->--}}
{{--                                            <img id="captcha-img"--}}
{{--                                                 src="{{ captcha_src() }}"--}}
{{--                                                 style="height: 45px; max-width: 120px; width: auto; cursor: pointer; border-radius: 5px; object-fit: contain;"--}}
{{--                                                 onclick="this.src=this.src+'?'+Math.random()"--}}
{{--                                                 title="点击刷新"--}}
{{--                                                 alt="captcha">--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}

                                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                        <input
                                type="text"
                                class="form-control {{ $errors->has('google_2fa_code') ? 'is-invalid' : '' }}"
                                name="google_2fa_code"
                                placeholder="{{ \Asundust\DcatAuthGoogle2Fa\DcatAuthGoogle2FaServiceProvider::trans('dcat-auth-google-2fa.google_2fa_code_tips') }}"
                                value="{{ old('google_2fa_code') }}"
                        >

                        <div class="form-control-position">
                            <i class="fa fa-google"></i>
                        </div>

                        <label for="code">{{ \Asundust\DcatAuthGoogle2Fa\DcatAuthGoogle2FaServiceProvider::trans('dcat-auth-google-2fa.google_2fa_code') }}</label>

                        <div class="help-block with-errors"></div>
                        @if($errors->has('google_2fa_code'))
                            <span class="invalid-feedback text-danger" role="alert">
                                @foreach($errors->get('google_2fa_code') as $message)
                                    <span class="control-label" for="inputError"><i class="feather icon-x-circle"></i> {{$message}}</span>
                                    <br>
                                @endforeach
                            </span>
                        @endif
                    </fieldset>
                                    <div class="form-group d-flex justify-content-between align-items-center">
                                        <div class="text-left">
                                            <fieldset class="checkbox">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input id="remember" name="remember"  value="1" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                                <span class="vs-checkbox--check">
                                                                  <i class="vs-icon feather icon-check"></i>
                                                                </span>
                                                            </span>
                                                    <span> {{ trans('admin.remember_me') }}</span>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary float-right login-btn">

                                        {{ __('admin.login') }}
                                        &nbsp;
                                        <i class="feather icon-arrow-right"></i>
                                    </button>

                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>


<script>
    Dcat.ready(function () {
        // ajax表单提交
        $('#login-form').form({
            validate: true,
            success: function(response) {
                // 如果返回 JSON 并且有错误信息
                if (response.status === false) {
                    refreshCaptcha(); // 重新加载验证码
                }
            },
            error: function(response){
                refreshCaptcha(); // 重新加载验证码
            }
        });
        function refreshCaptcha() {
            var captchaImg = document.getElementById('captcha-img');
            if (captchaImg) {
                captchaImg.src = captchaImg.src.split('?')[0] + '?' + Math.random();
            }
        }
    });
    var timer = null;
    $('#password_login').on('click',function (e) {
        clearInterval(timer);
    });
    $('#qrcode_login').on('click',function (e) {
        var formdata = {};
        formdata._token = '{{csrf_token()}}';
        // 自行实现扫码登陆 url('/admin/auth/getQrcode')
        /*$.ajax('', {data: formdata}).then(function (resp) {
            if(resp.status){
                $('#qrcode-box').html(resp.data.qrcode_html);
                timer = setInterval(() => {
                    // 请求参数是二维码中的场景值 url('/admin/auth/qrcode-login-check')
                    $.ajax('', {params: {wechat_flag: '',_token:'{{csrf_token()}}'}}).then(response => {
                        let result = response.data;
                        if (result.data) {
                            window.location.href = '/'
                        }
                    })
                }, 2000)
            }

        });*/
    });
</script>
