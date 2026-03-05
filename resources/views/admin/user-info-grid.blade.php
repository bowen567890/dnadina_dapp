@php
    /** @var \App\Models\User|\Illuminate\Database\Query\Builder $user ****/
$nameAuth = false;
if (!empty($itns->status) &&  $itns->status == 1) $nameAuth = true;

@endphp
<div style="">

    <div >
        ID：{{$user->id}} - {{$user->created_at->diffForHumans()}}注册
<!--         @if($user->valid_status == 1) -->
<!--          <span style="background-color: #28a745; color: white;margin-left: 10px; padding: 4px 8px; border-radius: 4px; font-size: 8px; font-weight: bold;">✓ 有效用户</span>
<!--         @else -->
<!--          <span style="background-color: #dc3545; color: white; margin-left: 10px; padding: 4px 8px; border-radius: 4px; font-size: 8px; font-weight: bold;">✗ 无效用户</span>
<!--         @endif -->
        <div style="margin-top: 10px">
            <p>
                地址： {{$user->address}}
            </p>
             <p>
                邀请码： {{$user->code}}
            </p>
             <p>
                注册时间： {{$user->created_at}}
            </p>
        </div>
    <div style="margin-top: 5px;" class="flex">
        <div>{!! $model !!}</div>
 		<div class="margin-left">{!! $userZhiList !!}</div>
 		<div class="margin-left">{!! $userTeamList !!}</div>
    </div>
</div>

