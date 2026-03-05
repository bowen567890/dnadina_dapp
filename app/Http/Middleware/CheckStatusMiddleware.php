<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MyRedis;

class CheckStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user->status != 1){
            return (new \App\Util\Response())->fail(401,Lang('暂无权限'))->json();
        }
        
        if (config('env.CHECK_LAST_TOKEN'))
        {
            $last_token = '';
            $MyRedis = new MyRedis();
            $lockKey = 'last_token:'.$user->id;
            if ($MyRedis->exists_key($lockKey)) {
                $last_token = $MyRedis->get_key($lockKey);
            } else {
                return (new \App\Util\Response())->fail(401,Lang('暂无权限'))->json();
            }
            //判断最后登入token
            $token = request()->header('Authorization', '');
            if (!$token || !$last_token || ($token!=$last_token)) {
                return (new \App\Util\Response())->fail(401,Lang('暂无权限'))->json();
            }
        }
        
        return $next($request);
    }
}
