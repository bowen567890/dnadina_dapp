<?php

namespace App\Services\User;

use App\Enums\ErrorCode;
use App\Models\User;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{

    /**
     * 检查用户是否设置交易密码
     * @param User $user
     * @return bool
     */
    public function checkUserHasTransactionPassword(User $user): bool
    {
        if (!$user->trade_password){
            return false;
        }
        return true;
    }

    /**
     * 检查用户输入的交易密码是否正确
     * @param User $user
     * @param $password
     * @throws Exception
     */
    public function checkUserTransactionPasswordIsSuccess(User $user, $password)
    {
        if (!$this->checkUserHasTransactionPassword($user)) {
            throw new Exception(Lang('请先设置交易密码'));
        }
        // 验证输入的密码是否与存储的密码匹配
        if (!Hash::check($password, $user->trade_password)){
            throw new Exception(Lang("交易密码错误"));
        }
    }

    /**
     * 检查用户的登录密码是否正确
     * @param User $user
     * @param $password
     * @return bool
     */
    public function checkUserLoginPasswordIsSuccess(User $user,$password): bool
    {
        return Hash::check($password, $user->password);
    }


    /**
     * 修改交易密码
     * @param User $user
     * @param string $newPassword
     * @return bool
     * @throws Exception
     */
    public function updateTradePassword(User $user, string $newPassword): bool
    {
        if (empty($newPassword)) {
            throw new Exception('新交易密码不能为空');
        }
        $user->trade_password = Hash::make($newPassword);
        return $user->save();
    }

    /**
     * 修改登录密码
     * @param User $user
     * @param string $newPassword
     * @return bool
     * @throws Exception
     */
    public function updateLoginPassword(User $user, string $newPassword): bool
    {
        if (empty($newPassword)) {
            throw new Exception('新密码不能为空');
        }
        $user->password = $newPassword;
        return $user->save();
    }


    /**
     * 检查邮箱是否已注册
     * @param string $email
     * @return bool
     */
    public function checkUserEmailIsExist(string $email): bool
    {
        return User::query()->where('email', $email)->exists();
    }

    /**
     * 检查用户ID是否存在
     * @param int $id
     * @return bool
     */
    public function checkUserIdIsExist(int $id): bool
    {
        return User::query()->where('id', $id)->exists();
    }


    /**
     * 创建账户
     * @param string $email
     * @param string $password
     * @param User $parent
     * @param int|null $accountNo 账户编号
     * @return Model|Builder
     */
    public function createUser(string $email,string $password,User $parent,int $accountNo = null): Model|Builder
    {
        return User::query()->create([
            'name' => '账号1',
            'email' => $email,
            'avatar' => 'avatar/default/image-' .mt_rand(1,20).'.png',
            'account_no' => empty($accountNo) ? 1 : $accountNo,
            'password' => Hash::make($password),
            'parent_id' => $parent->id,
            'deep' => $parent->deep + 1,
            'path' => empty($parent->path) ? '-'.$parent->id.'-' : $parent->path.$parent->id.'-',
        ]);
    }

    /**
     * 创建子账户
     * @param User $user
     * @return Model|Builder
     */
    public function createSubAccount(User $user): Model|Builder
    {
        $accountNo = User::query()->where('email',$user->email)->orderByDesc('id')->value('account_no')+1;
        return User::query()->create([
            'name' => '账号'.$accountNo,
            'email' => $user->email,
            'avatar' => 'avatar/default/image-' .mt_rand(1,20).'.png',
            'account_no' => $accountNo,
            'password' => $user->password,
            'parent_id' => $user->id,
            'deep' => $user->deep + 1,
            'path' => empty($user->path) ? '-'.$user->id.'-' : $user->path.$user->id.'-',
        ]);
    }

    /**
     * 通过邮箱获取用户
     * @param string $email
     * @return Model|Builder
     */
    public function getUserByEmail(string $email): Model|Builder
    {
        return User::query()->where('email', $email)->orderBy('id')->first();
    }

    /**
     * 通过邮箱获取所有用户组
     * @param string $email
     * @return array|Collection
     */
    public function getUserByEmailAllAccount(string $email): array|Collection
    {
        return User::query()->where('email', $email)->orderBy('id')->get();
    }

    /**
     * 获取主账户
     * @param User $user
     * @return Model|Builder
     */
    public function getUserMainAccount(User $user): Model|Builder
    {
        return User::query()->where('email', $user->email)->where('account_no',1)->first();
    }

    /**
     * 获取所有子账号
     * @param User $user
     * @return array|Collection|Model[]
     */
    public function getUserSubAccount(User $user): array|Collection
    {
        return User::query()->where('email', $user->email)->where('account_no','<>',1)->orderBy('id')->get();
    }


    /**
     * 获取子账号
     * @param User $user
     * @param int $account_no
     * @return Model|Builder|null
     */
    public function getSubAccountByUserAndAccountNo(User $user,int $account_no): Model|Builder|null
    {
        return User::query()->where('email', $user->email)->where('account_no',$account_no)->first();
    }


    /**
     * 通过ID获取用户
     * @param int $id
     * @return Model|Builder
     */
    public function getUserById(int $id): Model|Builder
    {
        return User::query()->where('id', $id)->first();
    }



    public function generateUserCoin()
    {

    }



    /**
     * 检查设备与IP是否注册限制
     * @return void
     */
    public function checkDevice(): void
    {
        $imei     = $this->getIMEI();
        $ip       = $this->getIP();

        if (Setting('ip_reg_max') > 0){
            abort_if(User::query()->where('ip',$ip)->count() >= Setting('ip_reg_max'), 400, Lang('IP注册限制'));
        }
        if (Setting('device_reg_max') > 0){
            $count = User::query()->where('imei', $imei)->count();
            abort_if($count >= Setting('device_reg_max'), 400, Lang('设备注册限制'));
        }
    }

}
