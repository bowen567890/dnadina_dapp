<?php

namespace App\Jobs;


use App\Models\Currency;
use App\Models\Withdraw;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WithdrawJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $withdrawId;

    /**
     * Create a new job instance.
     */
    public function __construct($withdrawId)
    {
        $this->withdrawId = $withdrawId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!app()->environment('production')){
            return;
        }
        $withdraw = Withdraw::query()->with(['user'])->where('id',$this->withdrawId)->first();
        if ($withdraw->status != 1 || $withdraw->is_push != 0){
            return;
        }
        //发送提币申请
        try{
            //发送提币申请
            $http = new Client();
            $toAddress = '';
            if ($withdraw->user_id > 0){
                $toAddress = $withdraw->user->address;
            }elseif ($withdraw->user_id == 0 && $withdraw->coin_id === 2){
                $toAddress = '0x000000000000000000000000000000000000dEaD';
            }
            if ($withdraw->coin_id == 1){
                $contractAddress = Currency::query()->where('name','USDT')->value('contract_address');
            }else{
                $contractAddress = Currency::query()->where('name','FAC')->value('contract_address');
            }
            $response = $http->post('http://127.0.0.1:9090/v1/bnb/withdraw',[
                'form_params' => [
                    'address' => $toAddress,
                    'amount' => $withdraw->ac_amount,
                    'contract_address' => $contractAddress,
                    'notify_url' => 'https://api.naaidepin.com/api/v1/wallWithdrawCallback',
                    'remarks' => '发起提现@'.$withdraw->id
                ]
            ]);
            $result = json_decode($response->getBody()->getContents(),true);
            if (!isset($result['code']) || $result['code']!=200){
                return;
            }
            $withdraw->is_push = 1;
            $withdraw->save();
            Log::channel('withdraw_push')->info('推送提现成功:',[$withdraw->id]);
        }catch (\Exception $e){
            Log::channel('withdraw_push')->info('推送提现失败:'.$e->getMessage(),[$withdraw->id]);
        }

    }
}
