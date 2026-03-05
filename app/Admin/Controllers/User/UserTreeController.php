<?php

namespace App\Admin\Controllers\User;

use App\Admin\Actions\Grid\User\UpdateLevel;
use App\Admin\Actions\Grid\User\UserFinance;
use App\Models\User;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use App\Models\RankConfig;

class UserTreeController extends AdminController
{
    public $nodeArr = [0=>'',1=>'联创'];
    public $nodeArr2 = [1=>'启航节点',2=>'飞跃节点',3=>'巅峰节点'];
    
    public $rankArr = [];
    public function __construct() {
        $rankArr = RankConfig::query()->orderBy('lv', 'asc')->pluck('name', 'lv')->toArray();
        $this->rankArr = array_merge([0=>''], $rankArr);
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
            
            $nodeArr = $this->nodeArr;
            $sourceTypeArr = $this->sourceTypeArr;
            $rankArr = $this->rankArr;
            $holdRankArr = $this->holdRankArr;
            $validStatusArr = $this->validStatusArr;
            
            $grid->column('id','用户')->display(function () {
                $html = "";
                // 将deep值作为data属性传递给前端，让JS来控制缩进
                $html .= "<div class='user-info' data-deep='" . $this->deep . "'>";
                $html .= "<div class='margin-top-xs user-row'>用户ID：" . $this->id;
//                 if ($this->valid_status == 1){
//                     $html .= '<span style="background-color: #28a745; color: white;margin-left: 10px; padding: 4px 8px; border-radius: 4px; font-size: 8px; font-weight: bold;">✓ 有效用户</span>';
//                 }else{
//                     $html .= '<span style="background-color: #dc3545; color: white;margin-left: 10px;  padding: 4px 8px; border-radius: 4px; font-size: 8px; font-weight: bold;">✗ 无效用户</span>';
//                 }
                $html .=  "</div>";
                $html .= "<div class='margin-top-xs user-row'>地址：" . $this->address . "</div>";
                $html .= "<div class='margin-top-xs user-row'>注册时间：" . $this->created_at . "</div>";
                $html .= "</div>";
                return $html;
            })->tree();

//             $grid->column('rank')->using($this->rankArr)->label('success');
//             $grid->column('node_rank')->using($this->nodeArr)->label();
            
            $grid->column('zhi_num','团队')->display(function () {
                $html = "";
                $html .= "<div class='margin-top-xs'>直推人数：" . $this->zhi_num . "</div>";
                $html .= "<div class='margin-top-xs'>团队人数：" . $this->team_num . "</div>";
                $html .= "<div class='margin-top-xs'>注册深度：" . $this->deep . "</div>";
                return $html;
            });
            
//             $grid->column('urank', '等级')->display(function () use($nodeArr, $rankArr, $sourceTypeArr, $holdRankArr, $validStatusArr)
//             {
//                 $html = "";
//                 $html .= "<div class='margin-top-xs'>VIP等级：" .$rankArr[$this->rank] . "</div>";
//                 $html .= "<div class='margin-top-xs'>节点等级：" .$nodeArr[$this->node_rank] . "</div>";
//                 $html .= "<div class='margin-top-xs'>有效用户：" .$validStatusArr[$this->valid_status] . "</div>";
//                 return $html;
//             });
            
            $grid->column('nodeyeji', '节点业绩')->display(function () {
                $html = "";
                $html .= "<div class='margin-top-xs'>个人业绩：" . $this->self_yeji . "</div>";
                $html .= "<div class='margin-top-xs'>团队业绩：" . $this->team_yeji . "</div>";
                //                 $html .= "<div class='margin-top-xs'>总计节点业绩：" . $this->total_yeji . "</div>";
                return $html;
            })->help('只统计购买节点的业绩');
                
            
         
//             $grid->column('balance', '余额')->display(function () {
//                 $html = "";
//                 if (empty($this->balance1)){
//                     $html .= "<div class='margin-top-xs'>USDT余额：0.000000";
//                 }else{
//                     $html .= "<div class='margin-top-xs'>USDT余额：" .$this->balance1->amount . "</div>";
//                 }
//                 if (empty($this->balance2)){
//                     $html .= "<div class='margin-top-xs'>FAC余额：0.000000";
//                 }else{
//                     $html .= "<div class='margin-top-xs'>FAC余额：" .$this->balance2->amount . "</div>";
//                 }
//                 return $html;
//             });

//             $grid->column('performance', '业绩')->display(function () {
//                 $html = "";
//                 $html .= "<div class='margin-top-xs'>个人业绩：" . $this->me_performance . "</div>";
//                 $html .= "<div class='margin-top-xs'>团队业绩：" . $this->team_performance . "</div>";
//                 $html .= "<div class='margin-top-xs'>累计业绩：" . $this->total_performance . "</div>";
//                 return $html;
//             });


//             $grid->column('created_at','注册日期')->display(function ($value) {
//                 return $value->toDateTimeString();
//             });

            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableDeleteButton();
            $grid->disableActions();
            
            
            $grid->model()->orderBy('id','asc');

//             $grid->actions(function ($actions) {
//                 $actions->append(new UpdateLevel($this->id));//修改等级
//                 $actions->append(new UserFinance($this->id));//财务操作
//             });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id','用户ID');
                $filter->equal('address');
                $filter->where('parentAddress',function ($query){
                    $parentAddress = $this->input;
                    $parent = User::query()->where('address',$parentAddress)->first();
                    if ($parent){
                        $query->where('id',$parent->parent_id);
                    }else{
                        $query->where('id','-1');
                    }
                },'上级地址');

//                 $filter->equal('status','状态')->select([1=>'正常',0=>'禁用']);
                $filter->between('created_at','注册时间')->date();
            });

            // 添加JavaScript来动态控制缩进
            Admin::script('
                $(document).ready(function() {
                    function adjustUserIndent() {
                        // 获取URL参数
                        const urlParams = new URLSearchParams(window.location.search);
                        const hasIdFilter = urlParams.get("id") && urlParams.get("id").trim() !== "";
                        const hasAddressFilter = urlParams.get("address") && urlParams.get("address").trim() !== "";

                        // 如果有ID或地址筛选，则调整缩进
                        if (hasIdFilter || hasAddressFilter) {
                            const userInfos = document.querySelectorAll(".user-info");
                            if (userInfos.length === 0) return;

                            // 获取所有deep值并找到最小值
                            let minDeep = Number.MAX_SAFE_INTEGER;
                            userInfos.forEach(function(userInfo) {
                                const deep = parseInt(userInfo.getAttribute("data-deep")) || 0;
                                if (deep < minDeep) {
                                    minDeep = deep;
                                }
                            });

                            // 设置相对缩进
                            userInfos.forEach(function(userInfo) {
                                const deep = parseInt(userInfo.getAttribute("data-deep")) || 0;
                                const relativeDeep = Math.max(0, deep - minDeep);
                                const indent = relativeDeep * 40;

                                // 应用缩进到所有子行
                                const userRows = userInfo.querySelectorAll(".user-row");
                                userRows.forEach(function(row) {
                                    row.style.marginLeft = indent + "px";
                                });
                            });
                        } else {
                            // 没有筛选时，使用原始深度缩进
                            const userInfos = document.querySelectorAll(".user-info");
                            userInfos.forEach(function(userInfo) {
                                const deep = parseInt(userInfo.getAttribute("data-deep")) || 0;
                                const indent = deep * 40;

                                const userRows = userInfo.querySelectorAll(".user-row");
                                userRows.forEach(function(row) {
                                    row.style.marginLeft = indent + "px";
                                });
                            });
                        }
                    }

                    // 页面加载完成后调整缩进
                    adjustUserIndent();

                    // 监听树形结构的展开事件，重新调整缩进
                    $(document).on("click", ".dcat-tree-branch", function() {
                        setTimeout(adjustUserIndent, 100);
                    });
                });
            ');
        });
    }

}
