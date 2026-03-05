<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserCoinAmountTypeEnum;
use Dcat\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * @property mixed $trade_password
 * @property mixed|string $password
 * @property mixed $google_secret
 * @property mixed $id
 * @property mixed $deep
 * @property mixed $parent_id
 * @property mixed $path
 * @property mixed $email
 * @property mixed $name
 * @property mixed $identity_status
 */
class User extends Authenticatable  implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, ModelTree;

    protected $titleColumn = 'name';

    protected $parentColumn = 'parent_id';

    /**
     * 序列化日期为标准格式
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address',
        'avatar',
        'code',
        'parent_id',
        'path',
        'deep',
        'status',
        'ip',
        'usdt',
        'register_type'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'trade_password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'trade_password' => 'hashed',
        'status' => 'boolean',
    ];

    /**
     * 获取格式化后的创建时间
     *
     * @return string
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    //USDT余额
    public function balance1()
    {
        return $this->hasOne(UsersCoinModel::class,'user_id','id')->where('type',UserCoinAmountTypeEnum::USDT);
    }

    //FAC余额
    public function balance2()
    {
        return $this->hasOne(UsersCoinModel::class,'user_id','id')->where('type',UserCoinAmountTypeEnum::FAC);
    }

    public function parent()
    {
        return $this->hasOne(User::class, 'id', 'parent_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
    
    /**
     * 处理余额,
     */
    public function handleUser($table, $user_id, $total, $type, $map = array())
    {
        if (!in_array($table, array('usdt','dht','dht_lock'))) {
            return false;
        }
//         if (in_array($table, ['usdt','btc','rwa','cast_power','mine_power'])) {
//             $month = date('Y-m');
//             $model = DB::table("user_{$table}{$month}");
//         } else {
//             $model = DB::table("user_{$table}");
//         }
        $model = DB::table("user_{$table}");
        
        $user = Db::table('users');
        if (!is_numeric($total)) {
            return false;
        }
        if (!in_array($type, array(1, 2))) {
            return false;
        }
        $r = null;
        $total = @bcadd($total, '0', 6);
        if ($type == 1) {
            $r = $user->where(array('id' => $user_id))->increment($table, $total);
        } else if ($type == 2) {
            $r = $user->where(array('id' => $user_id))->decrement($table, $total);
        }
        
        if (isset($map['date']) && $map['date']) {
            $date = $map['date'];
        } else {
            $date = date('Y-m-d H:i:s');
        }
        $add = array(
            'user_id' => $user_id,
            'type' => $type,
            'total' => $total,
            'ordernum' => isset($map['ordernum']) && $map['ordernum'] ? $map['ordernum'] : '',
            'cate' => isset($map['cate']) ? $map['cate'] : 0,
            'msg' => isset($map['msg']) ? $map['msg'] : '',
            'created_at' => $date,
            'updated_at' => $date,
        );
        
        if (isset($map['content']) && $map['content']) {
            $add['content'] = $map['content'];
        } else {
            $add['content'] = $add['msg'];
        }
        if (isset($map['from_user_id'])) {
            $add['from_user_id'] = $map['from_user_id'];
        }
        
        $addid = $model->insertGetId($add);
        if (($r || $r === 0) && $addid) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @param unknown $total 金额
     * @param unknown $num   单数
     * @param number $type
     */
    public function handleSelfYeji($user_id, $total=0, $type=1)
    {
        if ($type==1) {
            User::query()->where('id',$user_id)->update([
                'self_yeji'=>DB::raw("`self_yeji`+{$total}"),
                'total_yeji'=>DB::raw("`total_yeji`+{$total}")
            ]);
        } else {
            User::query()->where('id',$user_id)->update([
                'self_yeji'=>DB::raw("`self_yeji`-{$total}"),
                'total_yeji'=>DB::raw("`total_yeji`-{$total}")
            ]);
        }
    }
    
    
    /**
     * @param unknown $total 金额
     * @param number $type
     */
    public function handleTeamYeji($path, $total=0, $type=1)
    {
        $parentIds = explode('-',trim($path,'-'));
        $parentIds = array_reverse($parentIds);
        $parentIds = array_filter($parentIds);
        if ($parentIds) {
            if ($type==1) {
                User::query()->whereIn('id', $parentIds)->update([
                    'team_yeji'=>DB::raw("`team_yeji`+{$total}"),
                    'total_yeji'=>DB::raw("`total_yeji`+{$total}")
                ]);
            } else {
                User::query()->whereIn('id', $parentIds)->update([
                    'team_yeji'=>DB::raw("`team_yeji`-{$total}"),
                    'total_yeji'=>DB::raw("`total_yeji`-{$total}")
                ]);
            }
        }
    }
    
    /**
     * @param unknown $total 金额
     * @param number $type
     */
    public function handleSelfNode($user_id, $usdt=0, $type=1)
    {
        if ($type==1) {
            User::query()->where('id',$user_id)->update([
                'self_node'=>DB::raw("`self_node`+{$usdt}"),
                'total_node'=>DB::raw("`total_node`+{$usdt}"),
            ]);
        } else {
            User::query()->where('id',$user_id)->update([
                'self_node'=>DB::raw("`self_node`-{$usdt}"),
                'total_node'=>DB::raw("`total_node`-{$usdt}"),
            ]);
        }
    }
    
    /**
     * @param unknown $total 金额
     * @param number $type
     */
    public function handleTeamNode($path, $usdt=0, $type=1)
    {
        $parentIds = explode('-',trim($path,'-'));
        $parentIds = array_reverse($parentIds);
        $parentIds = array_filter($parentIds);
        if ($parentIds) {
            if ($type==1) {
                User::query()->whereIn('id', $parentIds)->update([
                    'team_node'=>DB::raw("`team_node`+{$usdt}"),
                    'total_node'=>DB::raw("`total_node`+{$usdt}"),
                ]);
            } else {
                User::query()->whereIn('id', $parentIds)->update([
                    'team_node'=>DB::raw("`team_node`-{$usdt}"),
                    'total_node'=>DB::raw("`total_node`-{$usdt}"),
                ]);
            }
        }
    }
    /**
     * 更新用户质押登记
     */
    public static function UpdateUserRank($user_id, $parent_id, $rankConf=[])
    {
        if ($parent_id>0)
        {
            if (!$rankConf) {
                $rankConf = RankConfig::GetListCache();
                $rankConf = array_column($rankConf, null, 'lv');
            }
            //等级升级
            $parentUser = User::query()
                ->where('id', $parent_id)
                ->first(['id','rank','zhi_yeji']);
            if ($parentUser && $rankConf)
            {
                $rank = 0;
                foreach ($rankConf as $val)
                {
                    if ($val['zhi_yeji']>$parentUser->zhi_yeji) {
                        continue;
                    }
                    $rank = $val['lv'];
                }
                
                if ($rank!=$parentUser->rank)
                {
                    if ($rank>$parentUser->rank) {
                        self::query()->where('id', $parentUser->id)->update(['rank'=>$rank]);
                    }
                }
            }
        }
    }
}
