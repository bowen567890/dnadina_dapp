@php
    /** @var User|Builder $user ****/


use App\Models\User;
use Illuminate\Database\Query\Builder;

if (!empty($itns->status) &&  $itns->status == 1) $nameAuth = true;

@endphp

@if($user_id == -1)
    <div style="">
        <div>
            <div style="margin-top: 10px">
                <p>
                    地址： 0xcAF5bC05B011AB4b163Ab7368Fc19dB8129aB66E(提现手续费50%地址)
                </p>
            </div>
        </div>
    </div>
@elseif($user_id == 0)
    <div style="">
        <div>
            <div style="margin-top: 10px">
                <p>
                    地址： 0x000000000000000000000000000000000000dEaD(黑洞地址)
                </p>
            </div>
        </div>
    </div>
@else
    <div style="">
        <div>
            ID：{{$user->id}} - {{$user->created_at->diffForHumans()}}注册  ：{{$user->valid_status == 1 ? '有效用户' : '无效用户'}}
            <div style="margin-top: 10px">
                <p>
                    地址： {{$user->address}}
                </p>
            </div>
        </div>
        <div style="margin-top: 5px;" class="flex">
            <div>{!! $model !!}</div>
            <div class="margin-left">{!! $logModel !!}</div>
        </div>
    </div>
@endif

