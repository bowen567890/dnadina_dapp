<?php

namespace App\Admin\Controllers\User;

use App\Admin\Actions\Grid\User\UpdateLevel;

use App\Models\User;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Form;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Http\JsonResponse;
use App\Models\RankConfig;
use Carbon\Carbon;
use App\Admin\Actions\Grid\AddUser;
use App\Admin\Actions\Grid\UpdateWallet;
use App\Admin\Actions\Grid\UpdateParent;
use App\Admin\Actions\Grid\SetBalanceNum;
use Dcat\Admin\Show;

class UserController extends AdminController
{
    public $nodeArr = [0=>'',1=>'联创'];
    public $nodeArr2 = [1=>'启航节点',2=>'飞跃节点',3=>'巅峰节点'];
    public $rankArr = [];
    public function __construct() {
        $rankArr = $this->rankArr2 = RankConfig::query()->orderBy('lv', 'asc')->pluck('name', 'lv')->toArray();
        $this->rankArr = array_merge([0=>'V0'], $rankArr);
    }
    public $sourceTypeArr = [0=>'', 1=>'购买',2=>'系统'];
    public $sourceTypeArr2 = [1=>'购买',2=>'系统'];
    public $withdrawArr = [0=>'禁止',1=>'允许'];
    public $holdRankArr = [0=>'否',1=>'是'];
    public $validStatusArr = [0=>'否',1=>'是'];
    
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
        return Grid::make(User::with(['parent']), function (Grid $grid) {
            
            $grid->tools(function  (Grid\Tools  $tools)  {
//                 $tools->append(new AddUser());
            });
            $grid->actions(function (Grid\Displayers\Actions $actions) use (&$grid){
//                 $actions->append(new SetBalanceNum());
//                 $actions->append(new UpdateWallet());
//                 $actions->append(new UpdateParent());
            });
            
            $nodeArr = $this->nodeArr;
            $sourceTypeArr = $this->sourceTypeArr;
            $rankArr = $this->rankArr;
            $holdRankArr = $this->holdRankArr;
            $validStatusArr = $this->validStatusArr;
            
            $grid->column('id','用户')->user();
//             $grid->column('code', '邀请码');
            
//             $grid->column('parent','上级')->display(function (){
//                 if (empty($this->parent)){
//                     return "";
//                 }else{
//                     $html = "";
//                     $html .= "<div class='margin-top-xs'>上级地址：".$this->parent->address."</div>";
//                     $html .= "<div class='margin-top-xs'>上级账户ID：".$this->parent_id."</div>";
//                     return $html;
//                 }
//             });

//             $grid->column('rank')->using($this->rankArr)->label('success');
//             $grid->column('node_rank')->using($this->nodeArr)->label();
            
//             $grid->column('balance', '余额')->display(function ()
//             {
//                 $html = "";
//                 $html .= "<div class='margin-top-xs'>DHT：" .$this->dht . "</div>";
//                 $html .= "<div class='margin-top-xs'>USDT：" .$this->usdt . "</div>";
//                 $html .= "<div class='margin-top-xs'>DHT锁仓：" .$this->dht_lock . "</div>";
//                 return $html;
//             });
            
            $grid->column('urank', '等级')->display(function () use($nodeArr, $rankArr, $sourceTypeArr, $holdRankArr, $validStatusArr)
            {
                $html = "";
//                 $html .= "<div class='margin-top-xs'>VIP等级：<span class='label' style='background:#21b978'>" .$rankArr[$this->rank] . "</span></div>";
//                 $html .= "<div class='margin-top-xs'>团队等级：" .$rankArr[$this->rank] . "</div>";
//                 $html .= "<div class='margin-top-xs'>保持等级：" .$holdRankArr[$this->hold_rank] . "</div>";
                $html .= "<div class='margin-top-xs'>节点等级：" .$nodeArr[$this->rank] . "</div>";
//                 $html .= "<div class='margin-top-xs'>有效用户：" .$validStatusArr[$this->valid_status] . "</div>";
                return $html;
            });
            
//             $grid->column('tibi', '提币')->display(function () {
//                 $arr = [0=>'禁止',1=>'允许'];
//                 $can_withdraw = $arr[$this->can_withdraw];
                
//                 $html = "";
//                 $html .= "<div class='margin-top-xs'>个人提币：" . $can_withdraw . "</div>";
//                 //                 $html .= "<div class='margin-top-xs'>总计业绩：" . $this->total_yeji . "</div>";
//                 return $html;
//             });
            
            $grid->column('tuijian', '推荐统计')->display(function () 
            {
                if($this->path) {
                    $path = $this->path."{$this->id}-";
                } else {
                    $path = "-{$this->id}-";
                }
//                 $teamNodeNum = User::query()
//                     ->where('node_rank', '>', 0)
//                     ->where('path', 'like', "{$path}%")
//                     ->count();
                
                $html = "";
                $html .= "<div class='margin-top-xs'>直推人数：" .$this->zhi_num . "</div>";
                $html .= "<div class='margin-top-xs'>团队人数：" .$this->team_num . "</div>";
//                 $html .= "<div class='margin-top-xs'>伞下节点人数：" .$teamNodeNum . "</div>";
                
                return $html;
            });
            
            $grid->column('nodeyeji', '节点业绩')->display(function () {
                $html = "";
                $html .= "<div class='margin-top-xs'>个人业绩：" . $this->self_yeji . "</div>";
                $html .= "<div class='margin-top-xs'>团队业绩：" . $this->team_yeji . "</div>";
//                 $html .= "<div class='margin-top-xs'>总计节点业绩：" . $this->total_yeji . "</div>";
                return $html;
            })->help('只统计购买节点的业绩');
            
//             $grid->column('pathlist', '推荐关系')->display('查看') // 设置按钮名称
//             ->modal(function ($modal) use ($nodeArr, $rankArr) {
//                 // 设置弹窗标题
//                 $modal->title('推荐关系');
//                 $path = $this->path;
//                 $parentIds = explode('-',trim($path,'-'));
//                 $parentIds = array_reverse($parentIds);
//                 $parentIds = array_filter($parentIds);
                
//                 $html = '<table class="table custom-data-table data-table" id="grid-table">
//                                     <thead>
//                                     	  <tr>
//                                     			 <th>上级ID</th>
//                                                  <th>层级</th>
//                                     			 <th>地址</th>
//                                     			 <th>节点等级</th>
//                                     	  </tr>
//                                     </thead>
//                                     <tbody>';
                
//                 if ($parentIds)
//                 {
//                     $list = User::query()
//                         ->whereIn('id',$parentIds)->orderBy('deep', 'desc')
//                         ->get(['id','address','deep','code','rank','node_rank'])
//                         ->toArray();
//                     if ($list)
//                     {
// //                         $config = TeamGasConfig::GetListCache();
                        
//                         foreach ($list as $val)
//                         {
// //                             $gas_rate = TeamGasConfig::GetGasRate($val['zhi_valid'], $config);
// //                             //团队gas比率 单独设置
// //                             $gas_rate = bccomp($val['gas_rate'], $gas_rate, 2)>0 ? $val['gas_rate'] : $gas_rate;
//                             $html.= "<tr><td>{$val['id']}</td>";
//                             $html.= "<td>{$val['deep']}</td>";
//                             $html.= "<td>{$val['address']}</td>";
//                             $html.= "<td>{$nodeArr[$val['node_rank']]}</td>";
//                             $html.= "</tr>";
//                         }
//                     }
//                 }
                
//                 $html.= "</tbody></table>";
//                 // 自定义图标
//                 return $html;
//             });
            
//             $grid->column('created_at','注册日期')->display(function ($value) {
//                 return $value->toDateTimeString();
//             });
                
            $grid->model()->orderBy('id','desc');

            $titles = [
                'id' => '用户ID',
                'address' => '钱包地址',
                'code' => '邀请码',
                'parent.id' => '上级ID',
                'parent.address' => '上级地址',
                'dht' => 'DHT',
                'usdt' => 'USDT',
                'rank' => '团队等级',
                'node_rank' => '节点等级',
                'zhi_num' => '直推人数',
                'team_num' => '团队人数',
                'self_node' => '个人节点业绩',
                'team_node' => '团队节点业绩',
                'self_yeji' => '个人业绩',
                'team_yeji' => '团队业绩',
                'can_withdraw' => '个人提币',
                'created_at' => '注册时间',
            ];
            
//             $grid->export($titles)->rows(function ($rows) 
//             {
//                 set_time_limit(0);
//                 ini_set('memory_limit','1024M');
                
//                 $rankArr = $this->rankArr;
//                 $nodeArr = $this->nodeArr;
//                 $withdrawArr = [0=>'禁止',1=>'允许'];
                
//                 foreach ($rows as $index => &$row)
//                 {
//                     $row['rank'] = $rankArr[$row['rank']];
//                     $row['node_rank'] = $nodeArr[$row['node_rank']];
//                     $row['can_withdraw'] = $withdrawArr[$row['can_withdraw']];
//                 }
//                 return $rows;
//             });
            

//             $grid->actions(function ($actions) {
//                 if (Admin::user()->can('user_update_level')){
//                     $actions->append(new UpdateLevel($this->id));//修改等级
//                 }
//                 if (Admin::user()->can('user_update_finance')){
//                     $actions->append(new UserFinance($this->id));//财务操作
//                 }
//             });

            $grid->disableRowSelector();
            $grid->disableEditButton();
            $grid->disableActions();
            $grid->disableViewButton();
            $grid->disableDeleteButton();
            $grid->disableCreateButton();
            $grid->scrollbarX();    			//滚动条
            $grid->paginate(10);				//分页

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id','用户ID');
                $filter->equal('address', '用户地址');
                $filter->equal('parent.address', '上级地址');
//                 $filter->equal('rank')->select($this->rankArr);
//                 $filter->equal('hold_rank', '保持等级')->select($this->holdRankArr);
//                 $filter->equal('node_rank')->select($this->nodeArr2);
//                 $filter->equal('node_source_type', '节点来源')->select($this->sourceTypeArr2);
//                 $filter->equal('can_withdraw', '个人提币')->select([0=>'禁止',1=>'允许']);
//                 $filter->equal('valid_status', '有效用户')->select($this->validStatusArr);
//                 $filter->equal('status','状态')->select([1=>'正常',0=>'禁用']);
//                 $filter->between('created_at','注册时间')->datetime();
                $filter->between('created_at','注册时间')->date();
                
//                 $filter->where('created_at', function ($query) {
//                     $start = $this->input['start'] ?? null;
//                     $end   = $this->input['end'] ?? null;
                    
//                     $query->when($start, fn ($q) => $q->where('created_at', '>=', Carbon::parse($start)->startOfDay()))
//                     ->when($end, fn ($q) => $q->where('created_at', '<=', Carbon::parse($end)->endOfDay()));
//                 }, '注册时间')->date();
                
            });
        });
    }
    
    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new User(), function (Show $show) {
            $show->field('id');
            $show->field('address');
            $show->disableDeleteButton();
            $show->disableEditButton();
        });
    }
    
    protected function form()
    {
        return Form::make(new User(), function (Form $form) {
            $form->display('id', '用户ID');
            $form->display('address', '钱包地址');
            $form->radio('can_withdraw', '个人提币')->required()->options($this->withdrawArr)->default(1);
            $form->select('rank', '团队等级')->required()->options($this->rankArr)->default(0);
            $form->radio('hold_rank', '保持等级')->required()->options($this->holdRankArr)->default(0)->help('手动设置等级此项需选择是');
//             $form->decimal('gas_rate', '团队GAS比率')->help('个人团队GAS分红比例,单独白名单设置(0.1=10%)');
            
            $form->saving(function (Form $form)
            {
                $id = $form->getKey();
//                 $gas_rate = @bcadd($form->gas_rate, '0', 2);
//                 if (bccomp($gas_rate, '1', 2)>0 || bccomp('0', $gas_rate, 2)>0) {
//                     return $form->response()->error('团队GAS比率不正确');
//                 }
            });
            
            $form->disableViewButton();
            $form->disableDeleteButton();
            $form->disableResetButton();
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
        });
    }
    
    /**
     * 删除
     */
    public function destroy($id)
    {
        return JsonResponse::make()->success('删除成功')->location('users');
    }

}
