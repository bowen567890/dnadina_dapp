<?php

namespace App\Admin\Controllers\User;

use App\Models\User;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;

class NewUserTreeController extends AdminController
{


    public function index(Content $content)
    {
        $parentAddress = request()->get('parent_address','');
        $is_previous = request()->get('is_previous',0);
        if (empty($parentAddress)){
            $topUser = User::query()->orderBy('id')->first();
            $firstAddress = [
                'key' => $topUser->id,
                'address' => $topUser->address,
                'zhi_num' => $topUser->zhi_num,
                'team_num' => $topUser->team_num,
                'me_performance' => $topUser->me_performance,
                'team_performance' => $topUser->team_performance,
                'total_performance' => $topUser->total_performance,
                'created_at' => $topUser->created_at->toDateTimeString(),
                '_childrenLoaded' => false,
                '_expanded' => false,
            ];
        }else{
            $user = User::query()->where('address',$parentAddress)->select(['id as user_id','parent_id as parent',
                'address','zhi_num','team_num','me_performance','team_performance','total_performance','created_at'])->first();
            if (empty($user)){
                $firstAddress = [];
            }else{
                //查询地址的上级
                if ($is_previous != 0 && $user['parent_id'] > 0){
                    $user = User::query()->where('id',$user->parent_id)->select(['id as user_id','parent_id as parent',
                        'address','zhi_num','team_num','me_performance','team_performance','total_performance','created_at'])->first();
                    $parentAddress = $user->parent_id;
                    $user->key = $user->user_id;
                    $user->created_at = $user->created_at->toDateTimeString();
                }else{
                    $firstAddress = [];
                }
                $firstAddress = $user;
                $firstAddress['_childrenLoaded'] = false;
                $firstAddress['_expanded'] = false;
            }
        }


        return $content
            ->header('推荐树图')
            ->body(view('admin.tree-chart1', [
                'firstAddress' => [$firstAddress],
                'parentAddress' => $parentAddress,
            ]));
    }


    protected function getChildrenUser()
    {
        $parentKey = request()->get('parentKey',0);
        $allUser = User::query()->where('parent_id',$parentKey)->select(['id as user_id','parent_id as parent',
            'address','zhi_num','team_num','me_performance','team_performance','total_performance','created_at'])->get()->toArray();
        foreach ($allUser as $k=>$v){
            $allUser[$k]['key'] = $v['user_id'];
        }
        return response()->json($allUser);
    }

}
