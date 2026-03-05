<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Request\Auth\Login\CheckRegisterRequest;
use App\Http\Request\Auth\Login\LoginOrRegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Services\HookService;
use App\Services\Web3\Web3SignatureService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Str;
use App\Models\MyRedis;

class LoginController extends ApiController
{
    private Web3SignatureService $web3SignatureService;

    public function __construct(Web3SignatureService $web3SignatureService)
    {
        $this->web3SignatureService = $web3SignatureService;
    }

    /**
     * 获取登录签名随机消息
     * @return JsonResponse
     */
    public function loginMessage(): JsonResponse
    {
        try {
            $message = $this->web3SignatureService->generateMessage();
            return $this->response(['message' => $message]);
        }catch (\Exception $exception){
            return $this->__responseError($exception->getMessage(), $exception->getCode());
        }
    }


    public function isRegister(CheckRegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->only('address');
            $isRegister = User::query()->where('address', $data['address'])->exists();
            return $this->response(['isRegister' => $isRegister]);
        }catch (\Exception $exception){
            return $this->__responseError($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Web3 钱包登录
     * @param LoginOrRegisterRequest $request
     * @return JsonResponse
     */
    public function login(LoginOrRegisterRequest $request): JsonResponse
    {
        try 
        {
            $data = $request->only('address','message', 'sign_message', 'code');
            // 验证签名
//             if (app()->environment('production')){
            if (config('env.CHECK_SIGN_MESSAGE')){
                if (!$this->web3SignatureService->verifySignature(
                    $data['address'],
                    $data['message'],
                    $data['sign_message']
                )) {
                    throw new \Exception(Lang('非法签名'), 400);
                }
            }
            
            $address = strtolower($data['address']);
            if (!checkBnbAddress($address)) {
                throw new \Exception(Lang('请选择正确的BSC地址'), 400);
            }
            
            // 查找或创建用户
            $user = User::query()->where('address', $address)->first();
            if (!$user) 
            {
                //查询是否有上级
                $parentUser = User::query()->where('code', $data['code'])->first();
                if (!$parentUser || $parentUser->status != 1) {
                    throw new \Exception(Lang('未找到邀请码'), 400);
                }
                do {
                    $code = Str::upper(Str::random(8));
                } while (User::query()->where('code', $code)->exists());
                
                $user = User::query()->create([
                    'address' => $address,
                    'code' => $code,
                    'status' => 1,
                    'parent_id' => $parentUser->id,
                    'deep' => $parentUser->deep + 1,
                    'path' => empty($parentUser->path) ? '-' . $parentUser->id . '-' : $parentUser->path . $parentUser->id . '-',
                    'ip' => $request->getClientIp(),
                ]);
                HookService::getService()->registerAfterHook($user);
            }

            // 检查用户状态
            if (!$user->status) {
                throw new \Exception(Lang("账户异常"), 400);
            }

            // 生成 JWT token
            $token = auth('api')->login($user);
            
            $MyRedis = new MyRedis();
            $token = 'Bearer '.$token;
            $lastKey = 'last_token:'.$user->id;
            $MyRedis->set_key($lastKey, $token);

            return $this->response([
                'token' => $token
            ]);
        } catch (\Exception $exception) {
            return $this->__responseError($exception->getMessage(), $exception->getCode());
        }
    }
}
