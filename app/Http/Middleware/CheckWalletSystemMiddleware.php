<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWalletSystemMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        //判断IP
        $trustIp = [
            '127.0.0.1','192.252.185.53'
        ];
        if (!in_array($request->ip(),$trustIp)){
            return (new \App\Util\Response())->fail(401,Lang('非信任请求'))->json();
        }
        return $next($request);
    }
}
