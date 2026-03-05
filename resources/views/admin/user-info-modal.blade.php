@php
    /** @var \App\Models\User $user ****/

$col = 3;

@endphp
<div class="container user-info">
    <h4>个人信息</h4>
    <div class="row">
        <div class="item col-{{$col}}">ID：{{$user->id}}</div>
<!--         <div class="item col-{{$col}}">地址：{{$user->address}}</div> -->
        <div class="item col-{{$col}}">注册深度：{{$user->deep}}</div>
        <div class="item col-{{$col}}">VIP等级：{{$rankArr[$user->rank]}}</div>
        <div class="item col-{{$col}}">节点等级：{{$nodeArr[$user->node_rank]}}</div>
    </div>
    
    <h4>团队信息</h4>
    <div class="row">
        <div class="item col-{{$col}}">团队人数：{{$user->team_num}}</div>
        <div class="item col-{{$col}}">直推人数：{{$user->zhi_num}}</div>
    </div>
    
    <h4>上级信息</h4>
    <div class="row">
        <div class="item col-{{$col}}">上级地址：{{empty($parent) ? '' : $parent->address}}</div>
        <div class="item col-{{$col}}">VIP等级：{{empty($parent) ? '' : $rankArr[$parent->rank]}}</div>
        <div class="item col-{{$col}}">节点等级：{{empty($parent) ? '' : $nodeArr[$parent->node_rank]}}</div>
        <div class="item col-{{$col}}">注册时间：{{empty($parent) ? '' : $parent->created_at}}</div>
    </div>
    
    
    
<!--     <h4 class="mt-1">钱包信息</h4> -->
<!--     <div class="row"> -->
<!--         <div class="item col-{{$col}}">提现次数：0</div> -->
<!--         <div class="item col-{{$col}}">充值次数：0</div> -->
<!--         <div class="item col-{{$col}}">USDT余额：0</div> -->
<!--         <div class="item col-{{$col}}">FAC余额：0</div> -->
<!--     </div> -->
</div>
<style>
    .user-info .row .item {
/*         padding: 5px 15px; */
    }
</style>
