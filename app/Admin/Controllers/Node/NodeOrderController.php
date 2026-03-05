<?php

namespace App\Admin\Controllers\Node;

use App\Models\NodeOrder;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use App\Models\RankConfig;
use Dcat\Admin\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\MyRedis;
use App\Models\NodeConfig;
use App\Models\PowerEvent;
use App\Models\User;

class NodeOrderController extends AdminController
{
    public $lvArr = [0=>'',1=>'启航节点',2=>'飞跃节点',3=>'巅峰节点'];
    public $sourceTypeArr = [1=>'购买',2=>'系统'];
    public $coinArr = [1=>'USDT', 3=>'BTC'];
    public $rankArr = [];
    public function __construct() {
        $rankArr = RankConfig::query()->orderBy('lv', 'asc')->pluck('name', 'lv')->toArray();
        $this->rankArr = array_merge([0=>''], $rankArr);
    }
    
    
    public function index(Content $content)
    {
        return $content
            ->header('列表')
            ->description('全部')
            ->breadcrumb(['text'=>'列表','url'=>''])
            ->body($this->grid());
    }

    protected function grid()
    {
        return Grid::make(NodeOrder::with(['user']), function (Grid $grid) {
            $grid->column('id')->sortable();
//             $grid->column('lv')->using($this->lvArr)->label('success');
            $grid->column('user_id');
            $grid->column('user.address', '用户地址');
            $grid->column('price');
//             $grid->column('give_rank')->using($this->rankArr)->label('success');
//             $grid->column('mine_income_rate', '每日收益比率')->help('每日挖矿产出比率(0.1=10%)');
//             $grid->column('income_info', '收益详情')->display(function () {
//                 $mine_income_rate = bcmul($this->mine_income_rate, '100', 3);
//                 $mine_income_rate = $mine_income_rate.'%';
//                 $html = "";
//                 $html .= "<div class='margin-top-xs'>每日收益比率：" .$mine_income_rate . "</div>";
//                 $html .= "<div class='margin-top-xs'>挖矿累计收益：" .$this->mine_income . "</div>";
//                 $html .= "<div class='margin-top-xs'>铸币累计收益：" .$this->cast_income . "</div>";
//                 return $html;
//             });

//             $grid->column('lock_info', '锁仓详情')->display(function () {
//                 $mine_income_rate = bcmul($this->mine_income_rate, '100', 3);
//                 $mine_income_rate = $mine_income_rate.'%';
//                 $html = "";
//                 $html .= "<div class='margin-top-xs'>锁仓数量：" .$this->give_lock_dht . "</div>";
//                 $html .= "<div class='margin-top-xs'>锁仓天数：" .$this->fee_day . "</div>";
//                 return $html;
//             });
            
//             $grid->column('output_coin','产出代币')->using($this->coinArr)->label('success')->help('USDT每天产出一次,BTC每10分钟产出');
            
//             $grid->column('source_type', '节点来源')->using($this->sourceTypeArr)->label('success');
//             $grid->column('ordernum');
            $grid->column('hash', '哈希')->display('点击查看') // 设置按钮名称
            ->modal(function ($modal) {
                // 设置弹窗标题
                $modal->title('交易哈希');
                // 自定义图标
                return $this->hash;
            });
            $grid->column('created_at');
//             $grid->column('updated_at')->sortable();

            $grid->model()->orderBy('id','desc');
            
            // $grid->setActionClass(Grid\Displayers\Actions::class); // 行操作按钮显示方式 图标方式
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                // 只有当 source_type == 2 时才显示删除按钮
                $actions->disableDelete(); //  禁用删除
//                 if ($actions->row->source_type!=2 || (bccomp($actions->row->give_mine_power, $actions->row->wait_mine_power, 6)>0) || (bccomp($actions->row->give_cast_power, $actions->row->wait_cast_power, 6)>0)) {
//                     $actions->disableDelete(); //  禁用删除
//                 }
//                 $actions->disableEdit();   //  禁用修改
//                 $actions->disableQuickEdit(); //禁用快速修改(弹窗形式)
//                 $actions->disableView(); //  禁用查看
            });
               
            $titles = [
                'id' => '订单ID',
                'lv' => '节点等级',
                'user_id' => '用户ID',
                'user.address' => '钱包地址',
                'source_type' => '节点来源',
                'price' => '节点价格',
                'give_rank' => '赠送等级',
                'hash' => '交易哈希',
                'created_at' => '创建时间',
            ];
            
//             $grid->export($titles)->rows(function ($rows)
//             {
//                 set_time_limit(0);
//                 ini_set('memory_limit','1024M');
                
//                 $lvArr = [0=>'',1=>'启航节点',2=>'飞跃节点',3=>'巅峰节点'];
//                 $rankArr = $this->rankArr;
//                 $sourceTypeArr = $this->sourceTypeArr;
                
//                 foreach ($rows as $index => &$row)
//                 {
//                     $row['give_rank'] = $rankArr[$row['give_rank']];
//                     $row['lv'] = $lvArr[$row['lv']];
//                     $row['source_type'] = $sourceTypeArr[$row['source_type']];
//                 }
//                 return $rows;
//             });
            
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
            $grid->disableActions();
            $grid->scrollbarX();    			//滚动条
            $grid->paginate(10);				//分页
            
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal('user_id');
                $filter->equal('user.address', '用户地址');
//                 $filter->equal('lv')->select($this->lvArr);
//                 $filter->equal('source_type', '节点来源')->select($this->sourceTypeArr);
                $filter->between('created_at','创建时间')->date();
            });
        });
    }
    
    protected function detail($id)
    {
        return Show::make($id, NodeOrder::with(['user']), function (Show $show) {
            $show->field('id');
            $show->field('lv')->using([1=>'启航节点',2=>'飞跃节点',3=>'巅峰节点'])->label('success');
            $show->field('user_id');
            $show->field('user.address', '钱包地址');
            $show->field('source_type', '节点来源')->using([1=>'购买',2=>'系统'])->label('success');
            $show->field('price');
            $show->field('give_rank')->using($this->rankArr)->label('success');
            $show->field('hash');
            $show->field('created_at');
            
            $show->disableDeleteButton();
            $show->disableEditButton();
        });
    }
    
    /**
     * 删除
     */
    public function destroy($id)
    {
        return JsonResponse::make()->success('删除成功')->location('node_order');
        
        $lockKey = 'NodeOrder:destroy:'.$id;
        $MyRedis = new MyRedis();
//                                                             $MyRedis->del_lock($lockKey);
        $ret = $MyRedis->setnx_lock($lockKey, 60);
        if(!$ret){
            return JsonResponse::make()->error('操作频繁');
        }
        
        $minute = date('i');
        $minute = intval($minute);
        
        $PowerEvent = PowerEvent::query()
            ->where('is_sync', 0)
            ->exists();
        if ($PowerEvent || in_array($minute, [0,1])) {
            $MyRedis->del_lock($lockKey);
            return JsonResponse::make()->error('系统结算中,请稍后尝试');
        }
        
        DB::beginTransaction();
        try
        {         
            $order = NodeOrder::query()->where(['id'=>$id, 'is_del'=>0])->first();
            if (!$order) {
                $MyRedis->del_lock($lockKey);
                return JsonResponse::make()->error('数据已更新')->location('node_order');
            }
            
            if ($order->source_type==1) {
                $MyRedis->del_lock($lockKey);
                return JsonResponse::make()->error('自行购买的节点不允许删除')->location('node_order');
            }
            
            if ((bccomp($order->give_mine_power, $order->wait_mine_power, 6)>0) || (bccomp($order->give_cast_power, $order->wait_cast_power, 6)>0)) {
                $MyRedis->del_lock($lockKey);
                return JsonResponse::make()->error('订单释放中不允许删除')->location('node_order');
            }
            
            NodeOrder::query()
                ->where('id', $id)
                ->update([
                    'is_del'=>1
                ]);
                
            NodeConfig::query()->where('lv', $order->lv)->update([
                'stock' => DB::raw("`stock`+1"),
                'sales' => DB::raw("`sales`-1")
            ]);
            
            $uup = [];
            $uup['rank'] = 0;
            $uup['node_rank'] = 0;
            $uup['give_rank'] = 0;
            $uup['node_source_type'] = 0;   //节点来源1购买2系统
            //更新节点等级
            $nOrder = NodeOrder::query()
                ->where('user_id', $order->user_id)
                ->where('is_del', 0)
                ->orderBy('lv', 'desc')
                ->first(['id','lv','give_rank','source_type']);
            if ($nOrder) {
                $uup['node_rank'] = $nOrder->lv;
                $uup['node_source_type'] = 2;
            }
            //更新赠送VIP等级
            $nOrder = NodeOrder::query()
                ->where('user_id', $order->user_id)
                ->where('is_del', 0)
                ->orderBy('give_rank', 'desc')
                ->first(['id','lv','give_rank','source_type']);
            if ($nOrder) {
                $uup['rank'] = $nOrder->give_rank;
                $uup['give_rank'] = $nOrder->give_rank;
                $uup['node_source_type'] = 2;
            }
            
            $nOrder = NodeOrder::query()
                ->where('user_id', $order->user_id)
                ->where('is_del', 0)
                ->where('source_type', 1)
                ->first(['id','lv','give_rank','source_type']);
            if ($nOrder) {
                $uup['node_source_type'] = 1;
            }
            if ($uup) {
                User::query()->where('id', $order->user_id)->update($uup);
            }
            
            $user = User::query()->where('id', $order->user_id)->first(['id','valid_status','gold','parent_id','btc','usdt','rwa']);
            
            
            $userModel = new User();
            if (bccomp($order->wait_mine_power, '0', 2)>0)
            {
                //分类1系统增加2系统扣除3购买节点
                $cates = ['msg'=>'系统扣除', 'cate'=>2, 'ordernum'=>$order->ordernum];
                $userModel->handleUser('mine_power', $order->user_id, $order->wait_mine_power, 2, $cates);
            }
            if (bccomp($order->wait_cast_power, '0', 2)>0) {
                //分类1系统增加2系统扣除3购买节点
                $cates = ['msg'=>'系统扣除', 'cate'=>2, 'ordernum'=>$order->ordernum];
                $userModel->handleUser('cast_power', $order->user_id, $order->wait_cast_power, 2, $cates);
            }
            
            if (bccomp($order->mine_income, '0', 10)>0)
            {
                //挖矿产出代币1USDT,3BTC
                if ($order->output_coin==1)
                {
                    if (bccomp($order->mine_income, $user->usdt, 10)>0)
                    {
                        $MyRedis->del_lock($lockKey);
                        return JsonResponse::make()->error('用户USDT余额不足,不可删除订单')->location('product_order');
                    }
                    //分类1系统增加2系统扣除4购买合约5提货扣除
                    $cates = ['msg'=>'系统扣除', 'cate'=>2, 'ordernum'=>$order->ordernum];
                    $userModel->handleUser('usdt', $order->user_id, $order->mine_income, 2, $cates);
                }
                else if ($order->output_coin==3)
                {
                    if (bccomp($order->mine_income, $user->btc, 10)>0)
                    {
                        $MyRedis->del_lock($lockKey);
                        return JsonResponse::make()->error('用户BTC余额不足,不可删除订单')->location('product_order');
                    }
                    //分类1系统增加2系统扣除4购买合约5提货扣除
                    $cates = ['msg'=>'系统扣除', 'cate'=>2, 'ordernum'=>$order->ordernum];
                    $userModel->handleUser('btc', $order->user_id, $order->mine_income, 2, $cates);
                }
            }
            
            if (bccomp($order->cast_income, '0', 10)>0)
            {
                if (bccomp($order->cast_income, $user->rwa, 10)>0)
                {
                    $MyRedis->del_lock($lockKey);
                    return JsonResponse::make()->error('用户RWA余额不足,不可删除订单')->location('product_order');
                }
                //分类1系统增加2系统扣除4购买合约5提货扣除
                $cates = ['msg'=>'系统扣除', 'cate'=>2, 'ordernum'=>$order->ordernum];
                $userModel->handleUser('rwa', $order->user_id, $order->cast_income, 2, $cates);
            }
            
            $PowerEvent = new PowerEvent();
            $PowerEvent->user_id = $order->user_id;
            $PowerEvent->order_id = $order->id;
            $PowerEvent->type = 1;  //事件类型1节点事件
            $PowerEvent->usdt = $order->price;
            $PowerEvent->mine_power = $order->wait_mine_power;
            $PowerEvent->cast_power = $order->wait_cast_power;
            $PowerEvent->ordernum = $order->ordernum;
            $PowerEvent->op_type = 2; //操作类型1增加2减少
            $PowerEvent->save();
            
            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $MyRedis->del_lock($lockKey);
            return JsonResponse::make()->error($e->getMessage());
        }
        
        return JsonResponse::make()->success('删除成功')->location('node_order');
    }

}
