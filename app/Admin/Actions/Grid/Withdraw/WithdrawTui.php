<?php

namespace App\Admin\Actions\Grid\Withdraw;

use App\Enums\QueueEnum;
use App\Jobs\WithdrawJob;
use App\Models\User;
use App\Models\Withdraw;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WithdrawTui extends RowAction
{


    public function handle(Request $request)
    {
        $id = $this->getKey();

        $withdraw = Withdraw::query()->find($id);
        if ($withdraw->status == 1 && $withdraw->is_push != 1){
            WithdrawJob::dispatch($withdraw->id)->onQueue(QueueEnum::Withdraw);
        }
        return $this->response()
            ->success("推送成功")
            ->alert();
    }

    public function confirm()
    {
        return ['是否确定推送'];
    }

    public function title()
    {
        return '推送钱包';
    }

}
