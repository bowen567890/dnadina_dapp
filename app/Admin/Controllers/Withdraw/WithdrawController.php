<?php

namespace App\Admin\Controllers\Withdraw;

use App\Admin\Actions\Grid\Withdraw\Audit;
use App\Admin\Actions\Grid\Withdraw\WithdrawTui;
use App\Admin\Extensions\Expoter\WithdrawExport;
use App\Models\Withdraw;
use App\Admin\Metrics\Withdraw\TotalWithdrawUsdt;
use App\Admin\Metrics\Withdraw\TotalWithdrawFac;
use App\Admin\Metrics\Withdraw\PendingWithdrawUsdt;
use App\Admin\Metrics\Withdraw\PendingWithdrawFac;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Admin;

class WithdrawController extends AdminController
{
    /**
     * page index
     */
    public function index(Content $content)
    {
        return $content
            ->header('列表')
            ->description('全部')
            ->breadcrumb(['text'=>'列表','url'=>''])
            ->body(function (Row $row) {
//                 $row->column(3, new TotalWithdrawUsdt());
//                 $row->column(3, new TotalWithdrawFac());
//                 $row->column(3, new PendingWithdrawUsdt());
//                 $row->column(3, new PendingWithdrawFac());
            })
            ->body($this->grid());
    }
    
    public $CoinTypeArr = [
        1=>'USDT',
//         2=>'BNB',
        3=>'DHT',
    ];
    public $statusArr = [
        0 => '待上链',
        1 => '已完成',
        2 => '已拒绝'
    ];

    protected function grid()
    {
        return Grid::make(Withdraw::with(['user']), function (Grid $grid) {
            $grid->column('id');
//             $grid->column('no');
            $grid->column('user_id','用户ID');
            
            $grid->column('receive_address');
//             $grid->column('num');
            $grid->column('coin_type')->using($this->CoinTypeArr)->label('success');
            
            
            $grid->column('coinnum', '提币信息')->display(function ()
            {
                $html = "";
                $html .= "<div class='margin-top-xs'>提币数量：" .$this->num . "</div>";
                $html .= "<div class='margin-top-xs'>手续费数量：" .$this->fee_amount . "</div>";
                $html .= "<div class='margin-top-xs'>实际提币数量：" .$this->ac_amount . "</div>";
                return $html;
            });
            
            $grid->column('fee');
            $grid->column('status')
            ->display(function () {
                $arr = [
                    0 => '待上链',
                    1 => '已完成',
                    2 => '已拒绝'
                ];
                $msg = $arr[$this->status];
                $colour = $this->status == 0 ? '#edc30e' : ($this->status == 1 ? '#21b978' : '#808080');
                return "<span class='label' style='background:{$colour}'>{$msg}</span>";
            });
            $grid->column('hash', '哈希')->display('点击查看') // 设置按钮名称
            ->modal(function ($modal) {
                // 设置弹窗标题
                $modal->title('交易哈希');
                // 自定义图标
                return $this->hash;
            });
            
//             $grid->column('othernum', '其他')->display(function ()
//             {
//                 $html = "";
//                 //BTC转换USDT提币
//                 if ($this->coin_type==3) 
//                 {
//                     $html .= "<div class='margin-top-xs'>提币数量(U)：" .$this->usdt_num . "</div>";
//                     $html .= "<div class='margin-top-xs'>手续费数量(U)：" .$this->fee_amount_usdt . "</div>";
//                     $html .= "<div class='margin-top-xs'>实际提币数量(U)：" .$this->ac_amount_usdt . "</div>";
//                     $html .= "<div class='margin-top-xs'>提币时代币价格：" .$this->coin_price . "</div>";
//                 }
              
//                 return $html;
//             });
            
            $grid->column('finsh_time');
            $grid->column('created_at')->sortable();
            
            
            $titles = [
                'id' => '订单ID',
                'user_id' => '用户ID',
                'user.address' => '接收方地址',
                'coin_type' => '提现币种',
                'num' => '提币数量',
                'fee_amount' => '手续费数量',
                'ac_amount' => '实际提币数量',
                'fee' => '手续费比例',
                'status' => '提币状态',
                'hash' => '交易哈希',
//                 'usdt_num' => '提币数量(U)',
//                 'fee_amount_usdt' => '手续费数量(U)',
//                 'ac_amount_usdt' => '实际提币数量(U)',
//                 'coin_price' => '提币时代币价格',
                'finsh_time' => '到账时间',
                'created_at' => '创建时间',
            ];
            
            $grid->export($titles)->rows(function ($rows)
            {
                set_time_limit(0);
                ini_set('memory_limit','1024M');
                
                $CoinTypeArr = $this->CoinTypeArr;
                $statusArr = $this->statusArr;
                
                foreach ($rows as $index => &$row)
                {
                    if ($row['coin_type']!=3) {
                        $row['usdt_num'] = '';
                        $row['fee_amount_usdt'] = '';
                        $row['ac_amount_usdt'] = '';
                        $row['coin_price'] = '';
                    }
                    $row['coin_type'] = $CoinTypeArr[$row['coin_type']];
                    $row['status'] = $statusArr[$row['status']];
                }
                return $rows;
            });
            
//             $grid->export();

//             $grid->model()->where('fee_status', '=', 1);
            $grid->model()->orderBy('id','desc');
            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableRowSelector();
            
            $grid->scrollbarX();    			//滚动条
            $grid->paginate(10);				//分页
            

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('user_id','用户ID');
//                 $filter->equal('no','订单号');
                $filter->equal('receive_address','收款地址');
                $filter->equal('status', '状态')->select([
                    0 => '待上链',
                    1 => '已完成',
                    2 => '已拒绝'
                ]);
                $filter->equal('coin_type', '币种')->select($this->CoinTypeArr);
                $filter->between('created_at','创建时间')->date();
            });
        });
    }
    protected function grid222222()
    {
        return Grid::make(Withdraw::with(['user']), function (Grid $grid) {
            $grid->column('user_id','用户')->baseUser();
            $grid->column('no','订单号');
            $grid->column('num','提现数量')->display(function ($value){
                if ($this->coin_id == 1){
                    return $value.'USDT';
                }else{
                    return $value.'FAC';
                }
            });
            $grid->column('fee','手续费率')->percent();
            $grid->column('fee_amount','手续费');
            $grid->column('ac_amount','到账金额');
            $grid->column('status','状态')->using([0=>'待审核',1=>'待打款',2=>'审核通过',3=>'审核未通过'])->badge();
            $grid->column('finsh_time','到账时间');
            $grid->column('created_at');
            $grid->disableCreateButton();
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('user_id','用户ID')->width(3);
                $filter->equal('user.address','用户地址')->width(3);
                $filter->equal('status')->select([0=>'待审核',1=>'待打款',2=>'审核通过',3=>'审核未通过'])->width(3);
            });
            $grid->disableQuickEditButton();
            $grid->export(new WithdrawExport());

            $grid->actions(function ($actions) {
                //推送到钱包系统
                if ($this->status == 1 && $this->is_push == 0 && Admin::user()->can('tui_wallet_system')) {
                    $actions->append(new WithdrawTui($this->id));
                }
                //审核
                if ($this->status == 0  && Admin::user()->can('withdraw_audit')){
                    $actions->append(new Audit($this->id));
                }
            });
        });
    }


}
