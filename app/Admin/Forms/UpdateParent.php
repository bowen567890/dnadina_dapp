<?php

namespace App\Admin\Forms;

use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Models\Administrator;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Contracts\LazyRenderable;
use App\Models\User;
use App\Models\UpdateParentLog;
use Illuminate\Support\Facades\DB;
use App\Models\MyRedis;
use App\Models\RankUplog;
use App\Models\PowerEvent;
use App\Models\RankConfig;

class UpdateParent extends Form implements LazyRenderable
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
        $in = $input;
        $id = $this->payload['id'] ?? 0;
        
        if (!isset($in['new_parent_address']) || !$in['new_parent_address']) {
            return $this->response()->error('请输入新上级地址');
        }
        $new_parent_address = trim($in['new_parent_address']);
        if (!checkBnbAddress($new_parent_address)) {
            return $this->response()->error('新上级钱包地址错误');
        }
        $new_parent_address = strtolower($new_parent_address);
        
        $MyRedis = new MyRedis();
        $lockKey = 'UpdateParent';
//         $MyRedis->del_lock($lockKey);
        $lock = $MyRedis->setnx_lock($lockKey, 600);
        if(!$lock){
            return $this->response()->error('操作频繁');
        }
        
        $minute = date('i');
        $minute = intval($minute);
        
        $PowerEvent = PowerEvent::query()
            ->where('is_sync', 0)
            ->exists();
        if ($PowerEvent || in_array($minute, [0,1])) {
            $MyRedis->del_lock($lockKey);
            return $this->response()->error('系统更新业绩中,请稍后尝试');
        }
        
        $newUser = User::query()->where('address', $new_parent_address)->first();
        if (!$newUser) {
            $MyRedis->del_lock($lockKey);
            return $this->response()->error('新上级用户不存在');
        }
        $new_parent_id = $newUser->id;
        
        $user = User::query()->where('id', $id)->first();
        if ($new_parent_id==$user->parent_id) 
        {
            $MyRedis->del_lock($lockKey);
            return $this->response()->error('新上级不能与旧上级相同');
        }
        
        if ($new_parent_id==$user->id)
        {
            $MyRedis->del_lock($lockKey);
            return $this->response()->error('不能设置自己为上级');
        }
        
        
        if($user->path) {
            $mpath = $user->path."{$id}-";
        } else {
            $mpath = "-{$id}-";
        }
        //伞下用户ID
        $isChild = User::query()
            ->where('id', '=', $new_parent_id)
            ->where('path', 'like', "{$mpath}%")
            ->first();
        if ($isChild) {
            $MyRedis->del_lock($lockKey);
            return $this->response()->error('不能更换到自己的下级');
        }
        
        //新上级
        $newPath = '';
        $newLevel = 1;
        $newPath = empty($newUser->path) ? '-'.$newUser->id.'-' : $newUser->path.$newUser->id.'-';
        $newLevel = $newUser->deep+1;
        
        DB::beginTransaction();
        try
        {
            $team_num = $user->team_num+1;
            
            $path = $user->path;
            $total_yeji = $user->total_yeji;
            $total_power = $user->total_power;
            $total_cast = $user->total_cast;
            $total_node = $user->total_node;
            
            if ($path) 
            {
                $parentIds = explode('-',trim($path,'-'));
                $parentIds = array_reverse($parentIds);
                $parentIds = array_filter($parentIds);
                if ($parentIds) 
                {
                    //旧上级修改数据
                    $yup = [
                        'team_num'=>DB::raw("`team_num`-{$team_num}"),               //团队人数减掉
                        'team_yeji'=>DB::raw("`team_yeji`-{$total_yeji}"),
                        'total_yeji'=>DB::raw("`total_yeji`-{$total_yeji}"),
                        'team_power'=>DB::raw("`team_power`-{$total_power}"),
                        'total_power'=>DB::raw("`total_power`-{$total_power}"),
                        'team_cast'=>DB::raw("`team_cast`-{$total_cast}"),
                        'total_cast'=>DB::raw("`total_cast`-{$total_cast}"),
                        'team_node'=>DB::raw("`team_node`-{$total_node}"),
                        'total_node'=>DB::raw("`total_node`-{$total_node}"),
                    ];
                    //团队有效用户
//                     $group_activate_num = $user->group_activate_num;
//                     if ($user->activate==1) {
//                         $group_activate_num = $group_activate_num+1;
//                     }
//                     $yup['group_activate_num'] = DB::raw("`group_activate_num`-{$group_activate_num}");
                    
                    User::query()->whereIn('id', $parentIds)->update($yup);
                }
            }
            //旧直推用户减掉 直推人数
            if ($user->parent_id>0) {
                $zup = [
                    'zhi_num'=>DB::raw("`zhi_num`-1")
                ];
                if ($user->valid_status==1) {
                    $zup['zhi_valid'] = DB::raw("`zhi_valid`-1");
                }
                User::query()->where('id', $user->parent_id)->update($zup);
            }
            
            //新上级
            if ($newPath) 
            {
                $parentIds = explode('-',trim($newPath,'-'));
                $parentIds = array_reverse($parentIds);
                $parentIds = array_filter($parentIds);
                if ($parentIds) {
                    //业绩
                    $yup = [
                        'team_num'=>DB::raw("`team_num`+{$team_num}"),
                        'team_yeji'=>DB::raw("`team_yeji`+{$total_yeji}"),
                        'total_yeji'=>DB::raw("`total_yeji`+{$total_yeji}"),
                        'team_power'=>DB::raw("`team_power`+{$total_power}"),
                        'total_power'=>DB::raw("`total_power`+{$total_power}"),
                        'team_cast'=>DB::raw("`team_cast`+{$total_cast}"),
                        'total_cast'=>DB::raw("`total_cast`+{$total_cast}"),
                        'team_node'=>DB::raw("`team_node`+{$total_node}"),
                        'total_node'=>DB::raw("`total_node`+{$total_node}"),
                    ];
                    User::query()->whereIn('id', $parentIds)->update($yup);
                }
            }
            if ($new_parent_id>0) {
                $zup = [
                    'zhi_num'=>DB::raw("`zhi_num`+1")
                ];
                if ($user->valid_status==1) {
                    $zup['zhi_valid'] = DB::raw("`zhi_valid`+1");
                }
                User::query()->where('id', $new_parent_id)->update($zup);
            }
            
            $old_parent_id = $user->parent_id;
            
            $UpdateParentLog = new UpdateParentLog();
            $UpdateParentLog->user_id = $user->id;
            $UpdateParentLog->old_parent_id = $user->parent_id;
            $UpdateParentLog->new_parent_id = $new_parent_id;
            $UpdateParentLog->group_num = $user->team_num;
            $UpdateParentLog->old_path = $user->path;
            $UpdateParentLog->new_path = $newPath;
            $UpdateParentLog->save();
            
            $oldLevel = $user->deep;
            
            $user->parent_id =  $new_parent_id;
            $user->path =  $newPath;
            $user->deep =  $newLevel;
            $user->save();
            
            $flag = 1;  //1加2减
            $diffLevel = 0;
            if ($newLevel>=$oldLevel) {
                $flag = 1;
                $diffLevel = $newLevel-$oldLevel;
            } else {
                $flag = 2;
                $diffLevel = $oldLevel-$newLevel;
            }
            
            
            $newPathArr[$user->id] = empty($newPath) ? '-'.$user->id.'-' : $newPath.$user->id.'-';
            //伞下用户ID
            $childList = User::query()
                ->where('path', 'like', "%-{$user->id}-%")
                ->orderBy('deep', 'asc')
                ->get(['id','parent_id','deep'])
                ->toArray();
            if ($childList) 
            {
                foreach ($childList as $cuser) 
                {
                    if ($flag==1) {
                        $up['deep'] = DB::raw("`deep`+{$diffLevel}");
                    } else {
                        $up['deep'] = DB::raw("`deep`-{$diffLevel}");
                    }
                    
                    if (!isset($newPathArr[$cuser['parent_id']])) 
                    {
                        if ($cuser['parent_id']>0) {
                            $ppuser = User::query()->where('id', $cuser['parent_id'])->first(['id','path']);
                            $newPathArr[$cuser['parent_id']] = $ppuser->path ? $ppuser->path.$ppuser->id.'-' : '-'.$ppuser->id.'-';
                            $up['path'] = $newPathArr[$cuser['parent_id']];
                        } else {
                            $up['path'] = '';
                        }
                    } 
                    else 
                    {
                        $up['path'] = $newPathArr[$cuser['parent_id']];
                    }
                    User::query()->where('id', $cuser['id'])->update($up);
                    if (!isset($newPathArr[$cuser['id']])) {
                        $newPathArr[$cuser['id']] = empty($up['path']) ? '-'.$cuser['id'].'-' : $up['path'].$cuser['id'].'-';
                    }
                }
            }
            
            //团队等级更新
            $userModel = new User();
            
            //等级配置
            $rankConf = RankConfig::GetListCache();
            $rankConf = $rankConf ? array_column($rankConf, null, 'lv') : [];
            //旧上级更新用户等级
            $userModel->UpdateUserRank($old_parent_id, $UpdateParentLog->old_path, $rankConf);
            //新上级更新用户等级
            $userModel->UpdateUserRank($new_parent_id, $newPath, $rankConf);
            
            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $MyRedis->del_lock($lockKey);
            return $this->response()->error($e->getMessage().$e->getLine());
            return $this->response()->error('操作失败');
        }
        $MyRedis->del_lock($lockKey);
        return $this
            ->response()
            ->success('操作成功')
            ->refresh();
    }
    
    /**
     * Build a form here.
     */
    public function form()
    {
        $this->display('user_id', '当前用户ID');
        $this->display('user_address', '当前用户地址');
        $this->display('old_parent_id', '旧上级ID');
        $this->display('old_parent_address', '旧上级地址');
        $this->text('new_parent_address', '新上级地址')->placeholder('填写新上级地址')->required();
        $this->disableResetButton();
    }
    
    /**
     * The data of the form.
     *
     * @return array
     */
    public function default()
    {
        $id = $this->payload['id'] ?? 0;
        
        $old_parent_id = 0;
        $old_parent_address = '';
        $user = User::query()->where('id', $id)->first(['id','address','parent_id']);
        if ($user->parent_id>0) {
            $parentUser = User::query()->where('id', $user->parent_id)->first(['id','address','parent_id']);
            $old_parent_id = $parentUser->id;
            $old_parent_address = $parentUser->address;
        }
        
        return [
            'user_id' => $user->id,
            'user_address' => $user->address,
            'old_parent_id' =>$old_parent_id,
            'old_parent_address' => $old_parent_address,
            'new_parent_address' => '',
        ];
    }
}
