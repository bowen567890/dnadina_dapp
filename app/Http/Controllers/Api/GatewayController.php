<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AcceptService;
use App\Util\AES;
use App\Models\MainCurrency;

use App\Enums\IncomeTypeEnum;
use App\Enums\QueueEnum;
use App\Enums\SystemEnum;
use App\Http\Controllers\Api\ApiController;
use App\Http\Request\Withdraw\WithdrawListRequest;
use App\Http\Request\Withdraw\WithdrawRequest;
use App\Jobs\WithdrawJob;
use App\Models\Withdraw;
use App\Services\User\BalanceService;
use App\Util\RedisLock;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\MyRedis;
use App\Models\User;
use App\Models\OrderLog;
use GuzzleHttp\Client;
use App\Models\WebsiteAnalyzeDaily;
use App\Models\WebsiteStatistic;
use Illuminate\Support\Facades\Log;


class GatewayController extends Controller
{
    
    public function aaa(Request $request)
    {
        return  \response()->json([
            'ip1' => $request->ip(),
            'ip2' => request()->getClientIp(),
            'env' => app()->environment('production'),
            'header' => $request->headers->all(),
        ]);
    }
    
    /**
     *
     *  访问
     *
     * @return mixed $data default string,
     *  else Exception
     *
     * @example
     *
     * 说明：
     *
     */
    public function accept(Request $request)
    {
        return (new AcceptService())->access($request);
    }

    public function decrypt(Request $request)
    {
        if (!app()->environment('production')) {
            echo AES::getInstance()->setKey($request->handshake)->decrypt($request->data)->get();
            exit;
        } else {
            exit('404 forbidden');
        }
    }

    public function encrypt(Request $request)
    {
        if (!app()->environment('production')) {
            $func    = $request->func;
            $response = (new AcceptService())->$func();
                return  \response()->json([
                    "handshake" => $request->handshake,
                    "code"      => 200,
                    "data"      => $response,
                    'encrypt'   => AES::getInstance()->setKey($request->handshake)->encrypt(json_encode($response))->get()
                ], 200);
            exit;
        } else {
            exit('404 forbidden');
        }
    }



   

    public function getBtcPrice1(Request $request)
    {
        $MainCurrency = new MainCurrency();
        $price = $MainCurrency->getBtcPrice1();
        
        return  \response()->json([
            'price' => $price
        ]);
    }
    
    public function getBtcPrice2(Request $request)
    {
        $MainCurrency = new MainCurrency();
        $price = $MainCurrency->getBtcPrice2();
        
        return  \response()->json([
            'price' => $price
        ]);
    }
    
    public function getBtcPrice3(Request $request)
    {
        $MainCurrency = new MainCurrency();
        $price = $MainCurrency->getBtcPrice3();
        
        return  \response()->json([
            'price' => $price
        ]);
    }
    
    public function getLpInfo(Request $request)
    {
        $in = $request->input();
        $contract_address = $in['contract_address'];
        $MainCurrency = new MainCurrency();
//         $price = $MainCurrency->getRwaPrice1();

//         $usdtContractAddress = config('env.USDT_ADDRESS');
//         $busdContractAddress = config('env.BUSD_ADDRESS');
//         $wbnbContractAddress = config('env.WBNB_ADDRESS');
//         $btcContractAddress = config('env.BTC_ADDRESS');
        
//         $token0 = strtolower($result['token0']);
//         $token1 = strtolower($result['token1']);
//         if ($token1==$usdtContractAddress || $token1==$busdContractAddress) {
//             $coin_price = @bcdiv($result['reserve1'], $result['reserve0'], 10);
//         } else {
//             $coin_price = @bcdiv($result['reserve0'], $result['reserve1'], 10);
//         }
       
        $price = $MainCurrency->getLpInfo($contract_address);
        
        return  \response()->json([
            'price' => $price
        ]);
    }
    
    public function getLpInfov3(Request $request)
    {
        $in = $request->input();
        $contract_address = $in['contract_address'];
        $MainCurrency = new MainCurrency();
        //         $price = $MainCurrency->getRwaPrice1();
        $price = $MainCurrency->getLpInfov3($contract_address);
        
        return  \response()->json([
            'price' => $price
        ]);
    }
    
    

}
