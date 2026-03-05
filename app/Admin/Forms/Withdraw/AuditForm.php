<?php

namespace App\Admin\Forms\Withdraw;

use App\Enums\IncomeTypeEnum;
use App\Enums\QueueEnum;
use App\Enums\UserCoinAmountTypeEnum;
use App\Jobs\WithdrawJob;
use App\Models\LevelConfig;
use App\Models\Withdraw;
use App\Services\User\BalanceService;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
Use App\Models\User;
use Dcat\Admin\Contracts\LazyRenderable;
use Illuminate\Support\Facades\Log;


class AuditForm extends Form implements LazyRenderable
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
        $id   = $this->payload['rid'] ?? null;
        $withdraw = Withdraw::query()->where('id', $id)->first();
        if (empty($withdraw)) {
            return $this->response()
                ->error("未找到数据")
                ->alert();
        }
        if ($withdraw['status'] != 0) {
            return $this->response()
                ->error("数据已被处理，无需继续处理")
                ->alert();
        }

        try {
            if ($input['status'] == 1) {
                $withdraw->status = 1;
                $withdraw->save();
                WithdrawJob::dispatch($withdraw->id)->onQueue(QueueEnum::Withdraw);
            } else {
                $withdraw->status = 3;
                $withdraw->finsh_time = date('Y-m-d H:i:s');
                $withdraw->save();
                $totalNum = $withdraw->num;
                BalanceService::getService()->addIncome($withdraw->user_id, $withdraw->coin_id, $totalNum, IncomeTypeEnum::WITHDRAWAL_BACKEND, '提现退回');
            }
            return $this->response()
                ->success("操作成功")
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
        $withdraw = Withdraw::query()->where('id',$id)->first();
        $this->text('订单号')->default($withdraw->no)->disable();
        $this->text('提现金额')->default($withdraw->num)->disable();
        $this->text('实际到账金额')->default($withdraw->ac_amount)->disable();

        $this->radio('status','是否通过')->options([1=>'审核通过',3=>'审核不通过'])->required();
    }

    /**
     * @return string[]
     * 设置默认值
     *  返回表单数据，如不需要可以删除此方法
     */
    public function default()
    {
        return [
            'status'         => '',
        ];
    }
}
