<?php

namespace App\Admin\Forms;

use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Models\Administrator;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Contracts\LazyRenderable;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\MyRedis;
use Illuminate\Support\Facades\DB;
use App\Models\OrderLog;
use App\Models\NodeConfig;
use App\Models\NodeOrder;
use App\Models\PowerEvent;
use App\Models\NodeOrderLog;
use App\Services\Recharge\RechargeService;

class AddNodeOrderForm extends Form implements LazyRenderable
{
    use LazyWidget; // 使用异步加载功能
    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function handle(array $input)
    {
        $in = $input;
        
        if (!isset($in['address']) || !$in['address'])  {
            return $this->response()->error('请输入节点地址');
        }
        $address = trim($in['address']);
        if (!checkBnbAddress($address)) {
            return $this->response()->error('钱包地址有误');
        }
        $address = strtolower($address);
        $lv = intval($in['lv']);
        
        $lockKey = 'AddNodeOrderForm';
        $MyRedis = new MyRedis();
//         $MyRedis->del_lock($lockKey);
        $lock = $MyRedis->setnx_lock($lockKey, 30);
        if(!$lock){
            return $this->response()->error('网络延迟');
        }
        
        DB::beginTransaction();
        try
        {          
            $user = User::query()
                ->where('address', $address)
                ->first(['id','address','rank','hold_rank','give_rank','node_rank','node_source_type','path']);
            if (!$user) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('钱包地址不存在');
            }
            
            $NodeConfig = NodeConfig::query()
                ->where('lv', $lv)
                ->first();
            if (!$NodeConfig) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('请选择节点');
            }
            
            if ($NodeConfig->stock<=0) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('节点库存不足');
            }
            
            if ($user->node_rank>$lv) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('不能降级购买');
            }
            if ($user->node_rank==$lv) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('此钱包地址已是节点');
            }
            
            //判断伞下节点人数
//             if ($NodeConfig->zhi_num>0)
//             {
//                 $user_id = $user->id;
//                 if($user->path) {
//                     $path = $user->path."{$user->id}-";
//                 } else {
//                     $path = "-{$user->id}-";
//                 }
//                 $zhi_num = User::query()
//                     ->where('node_rank', $NodeConfig->zhi_node)
//                     ->where('path', 'like', "{$path}%")
//                     ->count();
//                     //                 $zhi_num = User::where('parent_id', $user->id)
//                     //                     ->where('node_rank', $NodeConfig->zhi_node)
//                     //                     ->count();
//                 if ($NodeConfig->zhi_num>$zhi_num) {
//                     $MyRedis->del_lock($lockKey);
//                     return $this->response()->error('不满足购买条件');
//                 }
//             }
            
            
            $ordernum = get_ordernum();
            
            $order = new NodeOrderLog();
            $order->lv = $lv;
            $order->user_id = $user->id;
            $order->price = $NodeConfig->price;
            $order->pay_type = 1;
            $order->give_rank = $NodeConfig->give_rank;
            $order->give_mine_power = $NodeConfig->give_mine_power;
            $order->give_cast_power = $NodeConfig->give_cast_power;
            $order->output_coin = $NodeConfig->output_coin;
            $order->mine_income_rate = $NodeConfig->mine_income_rate;
            $order->ordernum = $ordernum;
            $order->source_type = 2;    //节点来源1购买2后台
            $order->save();
            
            $OrderLog = new OrderLog();
            $OrderLog->ordernum = $ordernum;
            $OrderLog->user_id = $user->id;
            $OrderLog->type = 1;    //订单类型1购买节点
            $OrderLog->save();
            
            $data = [
                'remarks' => $ordernum,
                'coin_token' => 'USDT',
                'amount' => $NodeConfig->price,
                'status' => 3,
            ];
            
            RechargeService::getService()->walletRecharge($data);
            
            DB::commit();
            $MyRedis->del_lock($lockKey);
            return $this
                ->response()
                ->success('操作成功')
                ->refresh();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $MyRedis->del_lock($lockKey);
            return $this->response()->error($e->getMessage());
        }
    }
    
    /**
     * Build a form here.
     */
    public function form()
    {
        $this->text('address','钱包地址')->required();
        $this->radio('lv','节点等级')->options([1=>'小节点',2=>'中节点',3=>'大节点'])->default(1)->required();
//         $this->radio('lv','节点等级')->options([1=>'小节点'])->default(1)->required();
    }
    
    /**
     * The data of the form.
     *
     * @return array
     */
    public function default()
    {
        return [
            'address' => '',
            'lv' => 1
        ];
    }
}
