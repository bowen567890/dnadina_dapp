<?php
namespace App\Http\Middleware;

use App\Util\Response;
use Closure;
use Illuminate\Http\Request;

class WhetherEncryptionPassesMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        try {
           if (app()->environment('production')) {
                $routs = config('env.uncrypted_routs');
                if (!in_array($request->path(), $routs)) {
                    $passToken = request()->header('Passtoken');
                    if (empty($passToken) || $passToken!=config('env.passToken')) {
                        throw new \Exception("Illegal access to services",  702345);
                    }
                }
           }
           return $next($request);
        } catch (\Exception $e) {
             return (new Response())->fail($e->getCode(),$e->getMessage())->json();
        }
    }
}
