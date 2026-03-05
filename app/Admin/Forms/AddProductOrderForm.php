<?php

namespace App\Admin\Forms;

use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Models\Administrator;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Contracts\LazyRenderable;
use App\Models\User;
use App\Models\MyRedis;
use Illuminate\Support\Facades\DB;
use App\Models\OrderLog;
use App\Models\Product;
use App\Models\ProductsContractCorrelation;
use App\Models\ProductsContract;
use App\Models\ProductsOrderLog;
use App\Models\ProductsOrder;
use App\Services\Recharge\RechargeService;

class AddProductOrderForm extends Form implements LazyRenderable
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
            return $this->response()->error('请输入用户地址');
        }
        $address = trim($in['address']);
        if (!checkBnbAddress($address)) {
            return $this->response()->error('钱包地址有误');
        }
        $address = strtolower($address);
        
        if (!isset($in['product_id']) || intval($in['product_id'])<=0) {
            return $this->response()->error('请选择商品');
        }
        $product_id = intval($in['product_id']);
        
        if (!isset($in['contract_id']) || intval($in['contract_id'])<=0) {
            return $this->response()->error('请选择合约');
        }
        $contract_id = intval($in['contract_id']);
        
        $num = '1';
        if (isset($in['num'])) {
            $num = intval($in['num']);
        }
        $num = $num>0 ? $num : '1';
        
        $lockKey = 'AddProductOrderForm';
        $MyRedis = new MyRedis();
        $lock = $MyRedis->setnx_lock($lockKey, 30);
        if(!$lock){
            return $this->response()->error('网络延迟');
        }
        
        DB::beginTransaction();
        try
        {          
            $user = User::query()
                ->where('address', $address)
                ->first(['id','address']);
            if (!$user) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('钱包地址不存在');
            }
            
            $Product = Product::query()
                ->where('id', $product_id)
                ->where('is_del', 0)
                ->first();
            if (!$Product) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('请选择商品');
            }
            
            if ($Product->stock<=0) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('商品库存不足');
            }
            
            $ProductsContractCorrelation = ProductsContractCorrelation::query()
                ->where('product_id', $product_id)
                ->where('contract_id', $contract_id)
                ->first();
            if (!$ProductsContractCorrelation) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('请选择合约');
            }
            
            $ProductsContract = ProductsContract::query()
                ->where('id', $contract_id)
                ->where('is_del', 0)
                ->first();
            if (!$ProductsContract) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('请选择合约');
            }
            
            $deliver_linkman = $deliver_tel = $deliver_address = '';
//             if ($ProductsContract->need_deliver==1) 
//             {
//                 if (!isset($in['deliver_linkman']) || !$in['deliver_linkman'] || !isset($in['deliver_tel']) || !$in['deliver_tel'] || !isset($in['deliver_address']) || !$in['deliver_address']) {
//                     $MyRedis->del_lock($lockKey);
//                     return $this->response()->error('收货信息不完善');
//                 }
//                 $deliver_linkman = filterInput($in['deliver_linkman']);
//                 $deliver_tel = filterInput($in['deliver_tel']);
//                 $deliver_address = filterInput($in['deliver_address']);
//                 if (!$deliver_linkman || !$deliver_tel || !$deliver_address) {
//                     $MyRedis->del_lock($lockKey);
//                     return $this->response()->error('收货信息不完善');
//                 }
//             }
            
            $ordernum = get_ordernum();
            
            $unit_price = $Product->price;
            $price = bcmul($unit_price, $num, 2);
            
            $give_gold = $Product->give_gold;
            $total_gold = bcmul($give_gold, $num, 2);
            
            $mine_power = bcmul($price, $ProductsContract->mine_power_multiple, 2);
            $cast_power = bcmul($price, $ProductsContract->cast_power_multiple, 2);
            
            $allFundRate = bcadd($ProductsContract->power_fund_rate, $ProductsContract->cast_market_rate, 2);
            if (bccomp($allFundRate, '1', 2)==0) {
                $mine_fund = bcmul($price, $ProductsContract->power_fund_rate, 2);
                $cast_market_fund = bcsub($price, $mine_fund, 2);
            } else {
                $mine_fund = bcmul($price, $ProductsContract->power_fund_rate, 2);
                $cast_market_fund = bcmul($price, $ProductsContract->cast_market_rate, 2);
            }
            
            $cast_fund = $market_fund = '0.00';
            if (bccomp($ProductsContract->cast_market_rate, '0', 2)>0 && bccomp($cast_market_fund, '0', 2)>0)
            {
                $castMarketRate = bcadd($ProductsContract->cast_fund_rate, $ProductsContract->market_fund_rate, 2);
                if (bccomp($castMarketRate, '1', 2)==0)
                {
                    $cast_fund = bcmul($cast_market_fund, $ProductsContract->cast_fund_rate, 2);
                    $market_fund = bcsub($cast_market_fund, $cast_fund, 2);
                } else {
                    $cast_fund = bcmul($cast_market_fund, $ProductsContract->cast_fund_rate, 2);
                    $market_fund = bcmul($cast_market_fund, $ProductsContract->market_fund_rate, 2);
                }
            }
            
            $order = new ProductsOrderLog();
            $order->user_id = $user->id;
            $order->product_id = $Product->id;
            $order->contract_id = $ProductsContract->id;
            $order->price = $price;
            $order->unit_price = $unit_price;
            $order->give_gold = $give_gold;
            $order->total_gold = $total_gold;
            $order->num = $num;
            $order->pay_type = 0;  // 0后台生成
            $order->mine_power = $mine_power;
            $order->cast_power = $cast_power;
            $order->mine_fund = $mine_fund;
            $order->cast_fund = $cast_fund;
            $order->market_fund = $market_fund;
            $order->mine_power_multiple = $ProductsContract->mine_power_multiple;
            $order->cast_power_multiple = $ProductsContract->cast_power_multiple;
            $order->mine_income_rate = $ProductsContract->mine_income_rate;
            $order->power_fund_rate = $ProductsContract->power_fund_rate;
            $order->cast_market_rate = $ProductsContract->cast_market_rate;
            $order->cast_fund_rate = $ProductsContract->cast_fund_rate;
            $order->market_fund_rate = $ProductsContract->market_fund_rate;
            $order->output_coin = $ProductsContract->output_coin;
            $order->monthly_income_rate = $ProductsContract->monthly_income_rate;
            $order->need_deliver = $ProductsContract->need_deliver;
            $order->deliver_linkman = $deliver_linkman;
            $order->deliver_tel = $deliver_tel;
            $order->deliver_address = $deliver_address;
            $order->ordernum = $ordernum;
            $order->source_type = 2;    //节点来源1购买2后台
            $order->save();
            
            $OrderLog = new OrderLog();
            $OrderLog->ordernum = $ordernum;
            $OrderLog->user_id = $user->id;
            $OrderLog->type = 2;    //订单类型1购买节点2合约订单
            $OrderLog->save();
            
            $data = [
                'remarks' => $ordernum,
                'coin_token' => 'USDT',
                'amount' => $price,
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
        // 获取商品列表
        $products = Product::query()
            ->where('is_del', 0)
            ->orderBy('id', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                $name = LocalDataGet($item->name);
                $name = is_array($name) ? ($name['CN'] ?? $name['EN'] ?? 'Unknown') : $name;
                return [$item->id => $name];
            })
            ->toArray();
        
        $this->text('address','用户地址')->required()->help('请输入用户钱包地址');
        $this->select('product_id','商品')->options($products)->required()->load('contract_id', admin_url('product_order/get-contracts'));
        $this->select('contract_id','合约')->required();
        $this->number('num','购买数量')->default(1)->min(1)->required();
        
        // 收货信息字段
//         $this->text('deliver_linkman','收货人')->help('如需发货，请填写收货信息');
//         $this->text('deliver_tel','联系电话');
//         $this->textarea('deliver_address','收货地址');
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
            'product_id' => '',
            'contract_id' => '',
            'num' => 1,
//             'deliver_linkman' => '',
//             'deliver_tel' => '',
//             'deliver_address' => ''
        ];
    }
}
