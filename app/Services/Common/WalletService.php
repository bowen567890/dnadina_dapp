<?php

namespace App\Services\Common;

use App\Services\BaseService;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WalletService extends BaseService
{


    public function rechargeLogic()
    {

    }


    public function withdrawLogic()
    {

    }


    /**
     * 生成地址
     * @param int $userId
     * @return string
     * @throws Exception
     */
    public function generateEthAddress(int $userId): string
    {
        try {
            $client = new Client([
                'base_uri' => env('WALLET_ADDRESS','http://127.0.0.1:9090'),
                'timeout' => 5.0,
                'verify'=> false
            ]);
            $response = $client->post('/v1/eth/generateAddress',[
                'form_params'=>[
                    'user_id' => $userId
                ]
            ]);
            $content = $response->getBody()->getContents();
            $content = json_decode($content,true);
            if (isset($content['code']) && $content['code'] == 200) {
                return $content['data']['address'];
            }
            return "";
        }catch (Exception $exception){
            Log::info('生成地址失败',$exception->getMessage());
            throw new Exception(Lang('生成地址失败'));
        }
    }


    /**
     * 生成地址
     * @param int $userId
     * @return string
     * @throws Exception
     */
    public function generateBscAddress(int $userId): string
    {
        try {
            $client = new Client([
                'base_uri' => env('WALLET_ADDRESS','http://127.0.0.1:9090'),
                'timeout' => 5.0,
                'verify'=> false
            ]);
            $response = $client->post('/v1/bnb/generateAddress',[
                'form_params'=>[
                    'user_id' => $userId
                ]
            ]);
            $content = $response->getBody()->getContents();
            $content = json_decode($content,true);
            if (isset($content['code']) && $content['code'] == 200) {
                return $content['data']['address'];
            }
            return "";
        }catch (Exception $exception){
            Log::info('生成地址失败',$exception->getMessage());
            throw new Exception(Lang('生成地址失败'));
        }
    }



    public function generateTrcAddress(int $userId): string
    {
        try {
            $client = new Client([
                'base_uri' => env('WALLET_ADDRESS','http://127.0.0.1:9090'),
                'timeout' => 5.0,
                'verify'=> false
            ]);
            $response = $client->post('/v1/tron/generateAddress',[
                'form_params'=>[
                    'user_id' => $userId
                ]
            ]);
            $content = $response->getBody()->getContents();
            $content = json_decode($content,true);
            if (isset($content['code']) && $content['code'] == 200) {
                return $content['data']['address'];
            }
            return "";
        }catch (Exception $exception){
            Log::info('生成地址失败',$exception->getMessage());
            throw new Exception(Lang('生成地址失败'));
        }
    }

}
