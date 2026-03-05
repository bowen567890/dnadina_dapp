<?php

namespace App\Exceptions;

use App\Util\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * 重写异常
     * @param $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        //限流
        if ($e instanceof ThrottleRequestsException) {
            return (new Response())->fail(429,'抱歉，您的访问受到限制(429)')->json();
        }

        //未认证
        if ($e instanceof AuthenticationException) {
            return (new Response())->fail(401,'会话已过期，请重新登录。（401）')->json();
        }

        if (app()->environment('production')) {
            if(env('APP_DEBUG')){
                return parent::render($request, $e);
            } else{
                Log::channel('server')->error($e);//线上环境不打印错误，直接记录日志
                // 线上环境,未知错误，则显示500
                return (new Response())->fail(500,"服务错误(500)")->json();
            }
        } else {
            return parent::render($request, $e);
        }
    }
}
