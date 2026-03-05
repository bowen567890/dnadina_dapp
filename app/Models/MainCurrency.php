<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MainCurrency extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'main_currency';
    
    public static function getLpPowerByInvest($invest,$num){
        $bhbPrice = self::query()->where('name','LP')->value('rate');
        $client = new Client();
        $response = $client->post('http://127.0.0.1:9090/api/wallet/pro/getSwapInfo',[
            'form_params' => [
                'mainChain' => 'BNB',
                'contractAddress' => MainCurrency::query()->where('name','LP')->value('contract_address'),
            ]
        ]);
        $lpResponse = json_decode($response->getBody()->getContents(),true);
        
        $a= number_format($lpResponse['obj']['reserve1']/$lpResponse['obj']['totalSupply'], 9, '.', '');
        $b = bcmul($num,2,9);
        $power = bcdiv(bcmul($b,$a,9),$bhbPrice,9);
        $acPower = bcmul($power,$invest->rate/100,9);
        $bhbNum = $num;
        return compact('bhbNum','power','acPower');
    }
    
    public function getLpInfo($contract_address)
    {
        try
        {
            $client = new Client();
            $response = $client->post('http://127.0.0.1:9090/v1/bnb/lpInfo',[
                'form_params' => [
                    'contract_address' => $contract_address
                ]
            ]);
            $result = $response->getBody()->getContents();
            if (!is_array($result)) {
                $result = json_decode($result, true);
            }
            if (!is_array($result) || !$result || !isset($result['code']) || $result['code']!=200 ||
                !isset($result['data']) || !isset($result['data']['reserve0']) || !isset($result['data']['reserve1']) ||
                !isset($result['data']['token0']) || !isset($result['data']['token1']))
            {
                return false;
            }
            else
            {
                return $result['data'];
            }
            
        }
        catch (\Exception $e)
        {
            return false;
        }
    }
    
    public function getLpInfov3($contract_address)
    {
        try
        {
            $client = new Client();
            $response = $client->post('http://127.0.0.1:9090/v1/bnb/lp3Info',[
                'form_params' => [
                    'contract_address' => $contract_address,
                    //'is_fan' => $is_fan  // 查询token1 转 token2 价格  is_fan = 1  否则传递 2
                ]
            ]);
            $result = $response->getBody()->getContents();
            if (!is_array($result)) {
                $result = json_decode($result, true);
            }
            
            if (!is_array($result) || !$result || !isset($result['code']) || $result['code']!=200 ||
                !isset($result['data']) || !isset($result['data']['amountOut']) ||
                !isset($result['data']['token0']) || !isset($result['data']['token1']))
            {
                return false;
            }
            else
            {
                return $result['data'];
            }
            
        }
        catch (\Exception $e)
        {
            return false;
        }
    }
    
    /**
     * 自动买币根据 订单号查询
     */
    public function getTransactionDetail($ordernum='')
    {
        $amount = '0';
        if ($ordernum)
        {
            try
            {
                $client = new Client();
                $response = $client->post('127.0.0.1:9099/getTransactionDetail',[
                    'form_params' => [
                        'contract_address' => env('RECHARGE_CONTRACT_ADDRESS'),   //查询自动买币的充值合约地址
                        'order_no' => $ordernum,
                    ],
                    'timeout' => 10,
                    'verify' => false
                ]);
                $result = $response->getBody()->getContents();
                if (!is_array($result)) {
                    $result = json_decode($result, true);
                }
                if (!is_array($result) || !$result || !isset($result['code']) || $result['code']!=200 ||
                    !isset($result['data']) || !isset($result['data']['out_num']))
                {
                    Log::channel('auto_trade_detail')->info('查询自动买币信息失败', $result);
                }
                else
                {
                    $pows = pow(10,18);
                    $amount = @bcadd($result['data']['out_num'], '0', 6);
                    if (bccomp($amount, '0', 6)>0) {
                        $amount = bcdiv($amount, $pows, 6);    //钱包系统返回来要除以18位
                    }
                }
            }
            catch (\Exception $e)
            {
                Log::channel('auto_trade_detail')->info('查询自动买币信息失败', ['error_msg'=>$e->getMessage().$e->getLine()]);
            }
        }
        return $amount;
        
    }
    
    /**
     * 老的接口 已经废弃
     */
    public function getAutoTradeDetail($hash)
    {
        $amount = 0;
        if ($hash)
        {
            try
            {
                $client = new Client();
                $response = $client->post('http://127.0.0.1:9090/v1/bnb/getAutoTradeDetail',[
                    'form_params' => [
                        'hash' => $hash
                    ],
                    'timeout' => 10,
                    'verify' => false
                ]);
                $result = $response->getBody()->getContents();
                if (!is_array($result)) {
                    $result = json_decode($result, true);
                }
                if (!is_array($result) || !$result || !isset($result['code']) || $result['code']!=200 ||
                    !isset($result['data']) || !isset($result['data']['amount']))
                {
                    Log::channel('auto_trade_detail')->info('查询自动买币信息失败', $result);
                }
                else
                {
                    $amount = @bcadd($result['data']['amount'], '0', 6);
                }
            }
            catch (\Exception $e)
            {
                Log::channel('auto_trade_detail')->info('查询自动买币信息失败', ['error_msg'=>$e->getMessage().$e->getLine()]);
            }
        }
        return $amount;
        
    }
    
    public function getBtcPrice1()
    {
        $price = '0';
        try
        {
            $btcAddress = config('env.BTC_ADDRESS');
            $url = "https://api.coingecko.com/api/v3/simple/token_price/binance-smart-chain?contract_addresses={$btcAddress}&vs_currencies=usd";
            $client = new Client();
            $response = $client->get($url, [
                'timeout' => 10,
                'verify' => false
            ]);
            
            $result = $response->getBody()->getContents();
            if (!is_array($result)) {
                $result = json_decode($result, true);
            }
            
            if (!is_array($result) || !$result || !isset($result[$btcAddress]) || !$result[$btcAddress] || !isset($result[$btcAddress]['usd']))
            {
                Log::channel('ave_price')->info('查询BTC价格失败');
            }
            else
            {
                $price = @bcadd($result[$btcAddress]['usd'], '0', 10);
            }
        }
        catch (\Exception $e)
        {
            Log::channel('ave_price')->info('查询BTC价格失败',['error_msg'=>$e->getMessage().$e->getLine()]);
        }
        
        return $price;
    }
    
    public function getBtcPrice2()
    {
        $price = '0';
        try
        {
            $btcAddress = config('env.BTC_ADDRESS');
            $url = "https://www.okx.com/priapi/v1/dx/market/v2/latest/info?tokenContractAddress={$btcAddress}&chainId=56&t=1729522781558";
            
            $client = new Client();
            $response = $client->get($url, [
                'timeout' => 10,
                'verify' => false
            ]);
            
            $result = $response->getBody()->getContents();
            if (!is_array($result)) {
                $result = json_decode($result, true);
            }
            
            if (!is_array($result) || !$result || !isset($result['code']) || $result['code']!='0' ||
                !isset($result['data']) || !is_array($result['data']) || !$result['data'] || !isset($result['data']['price'])
                )
            {
                Log::channel('ave_price')->info('查询BTC价格失败');
            }
            else
            {
                $price = @bcadd($result['data']['price'], '0', 10);
            }
        }
        catch (\Exception $e)
        {
            Log::channel('ave_price')->info('查询BTC价格失败',['error_msg'=>$e->getMessage().$e->getLine()]);
        }
        return $price;
    }
    
    public function getBtcPrice3()
    {
        $price = '0';
        try
        {
            $url = "https://api.binance.com/api/v3/ticker/price?symbol=BTCUSDT";
            
            $client = new Client();
            $response = $client->get($url, [
                'timeout' => 10,
                'verify' => false
            ]);
            
            $result = $response->getBody()->getContents();
            if (!is_array($result)) {
                $result = json_decode($result, true);
            }
            
            if (!is_array($result) || !$result || !isset($result['symbol']) || $result['symbol']!='BTCUSDT' ||
                !isset($result['price'])
                )
            {
                Log::channel('ave_price')->info('查询BTC价格失败');
            }
            else
            {
                $price = @bcadd($result['price'], '0', 10);
            }
        }
        catch (\Exception $e)
        {
            Log::channel('ave_price')->info('查询BTC价格失败',['error_msg'=>$e->getMessage().$e->getLine()]);
        }
        return $price;
    }
    
    
}
