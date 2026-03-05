<?php

namespace App\Services;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 *  网关服务
 *
 * @author   m.y
 * @example  App\Services\Gateway\AcceptService
 * @date  2022/6/17 9:13
 *
 * 使用说明
 * 1.统一请求网关
 * 2.HTTP请求与响应对称加密
 * 3.加密demo
 * 4.解密Demo
 */

use App\Util\AES;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class AcceptService
{

    /**
     * 网关
     * @param Request $request
     * @return JsonResponse
     */
    public function access(Request $request): JsonResponse
    {
		 try {
             if (empty($request->input('handshake'))) {
                 $parameters     = \GuzzleHttp\json_decode($request->getContent());
                 $params         = $parameters->data;
                 $handshake      = $parameters->handshake;
                 $timestamp      = $parameters->timestamp;
             }else {
                $handshake  = $request->input('handshake');
                $params     = $request->input('data');
                $timestamp  = $request->input('timestamp');
             }
             $this->__checkTimestamp($timestamp);
             $params         = AES::getInstance()->setKey($handshake)->decrypt($params)->get();
             $params         = \GuzzleHttp\json_decode($params,true);
             $request->merge($params['params']);
             $params['headers'] = array_merge(
                 $params['headers'] ?? [],[
                     'Accept' => 'application/json',
                     'Passtoken'=> 'cng5DB3brXHLhDYOnyMcE9r0oNT9wp',
                 ]);
		        foreach ($params['headers'] as $k => $v) {
		            $request->headers->set($k, $v);
		        }
		        if (!isset($params['uri']) || !isset($params['method'])){
                    throw new Exception('Your request is illegal, please do not attack.',404);
                }
		        $response = Route::dispatch($request::create($params['uri'], $params['method']));
                return $this->package($response);
		 } catch (Exception $e) {
		    return  \response()->json(["code" => $e->getCode(),"message"=>$e->getMessage()], 200);
		 }
    }

    /**
     * @throws Exception
     */
    public function __checkTimestamp($timestamp): void
    {
         $carbon  = Carbon::createFromTimestampMs($timestamp);
         $seconds = Carbon::make($carbon)->DiffInSeconds(Carbon::now());
         if ($seconds > 120 ) {
             throw new Exception("404 forbidden", 404);
         }
    }

    public function package($response)
    {
        $handshake = \Faker\Factory::create()->regexify('[A-Za-z0-9]{' . mt_rand(16, 16) . '}');
        $response  = $response->getData();
        return  \response()->json([
        	"handshake" => $handshake,
        	"code"      => 200,
        	"data"      => AES::getInstance()
                ->setKey($handshake)
                ->encrypt(json_encode($response))
                ->get()
        ], 200);
    }


}
