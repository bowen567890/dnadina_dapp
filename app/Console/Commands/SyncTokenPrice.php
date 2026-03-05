<?php
namespace App\Console\Commands;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Config;
use App\Models\MainCurrency;

class SyncTokenPrice extends Command
{

    // 自定义脚本命令签名
    protected $signature = 'sync:tokenprice';

    // 自定义脚本命令描述
    protected $description = '同步薄饼代币价格';


    // 创建一个新的命令实例
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $list = MainCurrency::query()
            ->where('id', '<>', 1)   //1USDT2MH3VV4BNB
            ->where('is_sync', '=', 1)
            ->orderBy('id', 'desc')
            ->get(['id','contract_address','contract_address_lp','pancake_cate'])
            ->toArray();
        if ($list)
        {
            $client = new Client();
            $usdtContractAddress = config('env.USDT_ADDRESS');
            $busdContractAddress = config('env.BUSD_ADDRESS');
            $wbnbContractAddress = config('env.WBNB_ADDRESS');
            $btcContractAddress = config('env.BTC_ADDRESS');
            
            $MainCurrency = new MainCurrency();
            
            foreach ($list as $val)
            {
                if (in_array($val['id'], [2,4])) 
                {
                    try
                    {
                        $contract_address = $val['contract_address_lp'];
                        $response = $client->post('http://127.0.0.1:9090/v1/bnb/lpInfo',[
                            'form_params' => [
                                'contract_address' => $contract_address
                            ],
                            'timeout' => 10,
                            'verify' => false
                        ]);
                        $result = $response->getBody()->getContents();
                        if (!is_array($result)) {
                            $result = json_decode($result, true);
                        }
                        
                        if (!is_array($result) || !$result || !isset($result['code']) || $result['code']!=200 ||
                            !isset($result['data']) || !isset($result['data']['reserve0']) || !isset($result['data']['reserve1']) ||
                            !isset($result['data']['token0']) || !isset($result['data']['token1']))
                        {
                            MainCurrency::query()->where('id', $val['id'])->update(['is_success'=>0]);
                            Log::channel('lp_info')->info('查询LP信息V2失败');
                        }
                        else
                        {
                            $token0 = strtolower($result['data']['token0']);
                            $token1 = strtolower($result['data']['token1']);
                            if ($token1==$usdtContractAddress || $token1==$busdContractAddress) 
                            {
                                //此代币是9位
                                if ($token0=='0x3cfed764cfed47926afd792a388823514135137f') {
                                    $result['data']['reserve0'] = bcmul($result['data']['reserve0'], '1000000000');
                                }
                                $coin_price = @bcdiv($result['data']['reserve1'], $result['data']['reserve0'], 10);
                            } 
                            else 
                            {
                                if ($token1=='0x3cfed764cfed47926afd792a388823514135137f') {
                                    $result['data']['reserve1'] = bcmul($result['data']['reserve1'], '1000000000');
                                }
                                $coin_price = @bcdiv($result['data']['reserve0'], $result['data']['reserve1'], 10);
                            }
                            
                            if (bccomp($coin_price, '0', 10)>0) {
                                MainCurrency::query()->where('id', $val['id'])->update(['rate'=>$coin_price,'is_success'=>1]);
                            } else {
                                MainCurrency::query()->where('id', $val['id'])->update(['is_success'=>0]);
                            }
                        }
                    }
                    catch (\Exception $e)
                    {
                        MainCurrency::query()->where('id', $val['id'])->update(['is_success'=>0]);
                        Log::channel('lp_info')->info('查询LP信息V2失败', ['error_msg'=>$e->getMessage().$e->getLine()]);
                    }
                }
                
                if ($val['id']==3) 
                {
                    $coin_price = $MainCurrency->getBtcPrice2();
                    if (bccomp($coin_price, '0', 10)<=0) {
                        $coin_price = $MainCurrency->getBtcPrice3();
                        if (bccomp($coin_price, '0', 10)<=0) {
                            $coin_price = $MainCurrency->getBtcPrice1();
                        }
                    }
                    if (bccomp($coin_price, '0', 10)>0) {
                        MainCurrency::query()->where('id', $val['id'])->update(['rate'=>$coin_price,'is_success'=>1]);
                    } else {
                        MainCurrency::query()->where('id', $val['id'])->update(['is_success'=>0]);
                    }
                }
            }
        }
    }
}
