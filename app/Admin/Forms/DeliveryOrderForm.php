<?php

namespace App\Admin\Forms;

use App\Models\ProductsOrder;
use App\Models\Order;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Illuminate\Support\Facades\DB;
use App\Models\MyRedis;
use App\Models\ProductsTakeOrder;

class DeliveryOrderForm extends Form implements LazyRenderable
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
        $id = $this->payload['id'];

        $lockKey = 'DeliveryOrderForm:'.$id;
        $MyRedis = new MyRedis();
//                 $MyRedis->del_lock($lockKey);
        $lock = $MyRedis->setnx_lock($lockKey, 60);
        if(!$lock){
            return $this->response()->error('操作频繁');
        }
        
        DB::beginTransaction();
        try 
        {
            $order = ProductsTakeOrder::query()->where('id',$id)->first();
            if($order->deliver_status!=1){
                $MyRedis->del_lock($lockKey);
                return $this
                    ->response()
                    ->error('该订单发货已完成')
                    ->refresh();
            }
            $order->ship_company = $input['ship_company'];
            $order->ship_no = $input['ship_no'];
            $order->deliver_status = 2; //订单状态0无需发货1待发货2已发货3已签收
//             $order->ship_time = date('Y-m-d H:i:s');
            $order->save();
            DB::commit();
            $MyRedis->del_lock($lockKey);
            return $this
                ->response()
                ->success('发货完成')
                ->refresh();

        }catch (\Exception $e){
            DB::rollBack();
            $MyRedis->del_lock($lockKey);
            return $this
                ->response()
                ->error('操作失败'.$e->getMessage())
                ->refresh();
        }

    }

    /**
     * Build a form here.
     */
    public function form()
    {
//         $this->select('ship_company','快递公司')->options([
//             '顺丰速运' => '顺丰速运',
//             '圆通速递' => '圆通速递',
//             '中通快递' => '中通快递',
//             '申通快递' => '申通快递',
//             '韵达速递' => '韵达速递',
//             '百世快递' => '百世快递',
//             '德邦快递' => '德邦快递',
//             '京东物流' => '京东物流',
//             '邮政EMS' => '邮政EMS',
//             '天天快递' => '天天快递',
//             '宅急送' => '宅急送',
//             '国通快递' => '国通快递',
//             '全峰快递' => '全峰快递',
//             '速尔快递' => '速尔快递',
//             '优速快递' => '优速快递'
//         ])->required();
        $this->text('ship_company','快递公司')->required();
        $this->text('ship_no','快递单号')->required();
    }

    /**
     * The data of the form.
     *
     * @return array
     */
    public function default()
    {
        return [

        ];
    }
}
