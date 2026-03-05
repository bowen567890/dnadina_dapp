<?php

namespace App\Admin\Actions\Grid;

use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Dcat\Admin\Widgets\Modal;

use App\Models\User;
use App\Models\MyRedis;
use App\Models\ProductsTakeOrder;
use Illuminate\Support\Facades\DB;

class DeliveryOrderCancel extends RowAction
{
    /**
     * @return string
     */
    protected $action;

    // 注意action的构造方法参数一定要给默认值
    public function __construct($title = null, $action = 1)
    {
        $this->title = '提货驳回';
        $this->action = $action;
    }

    public function handle(Request $request)
    {
        $id = $this->getKey();
        
        if (!isset($id) || !$id) {
            return $this->response()->error('参数错误');
        }
        
        $lockKey = 'DeliveryOrderForm:'.$id;
        $MyRedis = new MyRedis();
//         $MyRedis->del_lock($lockKey);
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
            $order->deliver_status = 4; //订单状态0无需发货1待发货2已发货3已签收4已驳回
            $order->save();
            
            $userModel = new User();
            $userModel->handleUser('gold', $order->user_id, $order->num, 1, ['cate'=>6, 'msg'=>'提货驳回', 'ordernum'=>$order->ordernum]);
            
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
     * @return string|array|void
     */
    public function confirm()
    {
        return ['确认驳回?', '驳回商品提货订单'];
    }

    /**
     * @return array
     */
    protected function parameters()
    {
        return [
            'action' => $this->action,
        ];
    }

}
