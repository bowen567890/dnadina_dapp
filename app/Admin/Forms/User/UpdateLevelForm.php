<?php

namespace App\Admin\Forms\User;

use App\Models\LevelConfig;
use App\Models\UsersLevel;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
Use App\Models\User;
use Dcat\Admin\Contracts\LazyRenderable;


class UpdateLevelForm extends Form implements LazyRenderable
{

    /**
     * 使用异步加载功能
     */
    use LazyWidget;

    /**
     * @param array $input
     * @return \Dcat\Admin\Http\JsonResponse
     * 处理请求
     */
    public function handle(array $input)
    {

        try {

            $id   = $this->payload['rid'] ?? null;
            $user = User::query()->find($id);
            $levelName = UsersLevel::query()->find($input['level_id'])->name;
            $user->level_id = $input['level_id'];
            $user->level_name = $input['level_id'] > 1 ? $levelName : 'T1';
            $user->backend_level_id = $input['level_id'];
            $user->save();

            return $this->response()
                ->success("等级已更新为：{$levelName}")
                ->alert()
                ->refresh();

        } catch (\Exception $e) {
           return $this->response()->error($e->getMessage());
        }
    }

    /**
     * 表单数据
     */
    public function form()
    {
        $id = $this->payload['rid'] ?? null;
        $user = User::query()->where('id',$id)->first();
        $this->text('UID')->default($user->id)->disable();
        $this->text('地址')->default($user->address)->disable();
        $this->select('level_id','等级')->default($user->level_id)->options(UsersLevel::query()->pluck('name','id')->toArray());
    }

    /**
     * @return string[]
     * 设置默认值
     *  返回表单数据，如不需要可以删除此方法
     */
    public function default()
    {
        return [
            'password'         => '',
        ];
    }
}
