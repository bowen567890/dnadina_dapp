<?php

namespace App\Admin\Forms\User;

use App\Enums\IncomeTypeEnum;
use App\Enums\UserCoinAmountTypeEnum;
use App\Enums\WebsiteAnalyzeEnum;
use App\Models\LevelConfig;
use App\Models\WebsiteAnalyze;
use App\Services\User\BalanceService;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
Use App\Models\User;
use Dcat\Admin\Contracts\LazyRenderable;


class UserFinanceForm extends Form implements LazyRenderable
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

            if ($input['operator_type'] == 1){
                BalanceService::getService()->addIncome($user->id,$input['amount_type'],$input['operator_num'],IncomeTypeEnum::BACKEND_OPERATION,$input['operator_remark']);
            }else{
                BalanceService::getService()->subIncome($user->id,$input['amount_type'],'-'.$input['operator_num'],IncomeTypeEnum::BACKEND_OPERATION,$input['operator_remark']);
            }
            if ($input['amount_type'] == UserCoinAmountTypeEnum::USDT){
                WebsiteAnalyze::addData(WebsiteAnalyzeEnum::BACKEND_RECHARGE_USDT,$input['operator_num']);
            }else{
                WebsiteAnalyze::addData(WebsiteAnalyzeEnum::BACKEND_RECHARGE_COIN,$input['operator_num']);
            }
            return $this->response()
                ->success("余额已调整")
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

        $this->select('amount_type','操作钱包')->options(UserCoinAmountTypeEnum::asSelectArray())->required();
        $this->radio('operator_type','操作方向')->options([1=>'增加',2=>'减少'])->required();
        $this->decimal('operator_num','操作金额')->required();
        $this->text('operator_remark','操作备注')->default('后台操作')->required();
    }

    /**
     * @return string[]
     * 设置默认值
     *  返回表单数据，如不需要可以删除此方法
     */
    public function default()
    {
        return [
            'operator_type'         => '',
            'operator_num'         => '',
            'operator_remark'         => '',
        ];
    }
}
