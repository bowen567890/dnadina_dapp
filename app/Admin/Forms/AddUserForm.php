<?php

namespace App\Admin\Forms;

use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Models\Administrator;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Contracts\LazyRenderable;
use App\Models\User;
use App\Models\MyRedis;
use Illuminate\Support\Facades\DB;
use Str;
use App\Services\HookService;

class AddUserForm extends Form implements LazyRenderable
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
        
        if (!isset($in['address']) || !$in['address'])  {
            return $this->response()->error('请输入钱包地址');
        }
        $address = trim($in['address']);
        if (!checkBnbAddress($address)) {
            return $this->response()->error('钱包地址有误');
        }
        $address = strtolower($address);
        
        $parent_address = trim($in['parent_address']);
        if (!checkBnbAddress($parent_address)) {
            return $this->response()->error('上级钱包地址有误');
        }
        $parent_address = strtolower($parent_address);
        
//         if (!isset($in['invitation_code']) || !$in['invitation_code'])  {
//             return $this->response()->error('请输入上级邀请码');
//         }
//         $invitation_code = trim($in['invitation_code']);
        
        $lockKey = 'AddUserForm';
        $MyRedis = new MyRedis();
//         $MyRedis->del_lock($lockKey);
        $lock = $MyRedis->setnx_lock($lockKey, 30);
        if(!$lock){
            return $this->response()->error('操作频繁');
        }
        
        DB::beginTransaction();
        try
        {          
            $user = User::query()
                ->where('address', $address)
                ->first(['id','address']);
            if ($user) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('此钱包地址已注册');
            }
            $parentUser = User::query()
                ->where('address', $parent_address)
//                 ->where('code', $invitation_code)
                ->first();
            if (!$parentUser || $parentUser->status != 1) {
                $MyRedis->del_lock($lockKey);
                return $this->response()->error('未找到上级用户');
            }
            
            do {
                $code = Str::upper(Str::random(8));
            } while (User::query()->where('code', $code)->exists());

            $user = User::query()->create([
                'address' => $address,
                'code' => $code,
                'status' => 1,
                'parent_id' => $parentUser->id,
                'deep' => $parentUser->deep + 1,
                'path' => empty($parentUser->path) ? '-' . $parentUser->id . '-' : $parentUser->path . $parentUser->id . '-',
//                 'ip' => $request->getClientIp(),
                'register_type' => 2
            ]);
            HookService::getService()->registerAfterHook($user);
            
            DB::commit();
            $MyRedis->del_lock($lockKey);
            return $this
                ->response()
                ->success('操作成功')
                ->refresh();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $MyRedis->del_lock($lockKey);
            return $this->response()->error($e->getMessage());
        }
    }
    
    /**
     * Build a form here.
     */
    public function form()
    {
        $this->text('address','钱包地址')->required();
        $this->text('parent_address','上级地址')->required();
//         $this->text('invitation_code','上级邀请码')->required();
    }
    
    /**
     * The data of the form.
     *
     * @return array
     */
    public function default()
    {
        return [
            'address' => '',
            'parent_address' => '',
//             'invitation_code' => '',
        ];
    }
}
