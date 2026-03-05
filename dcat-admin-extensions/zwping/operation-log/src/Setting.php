<?php

namespace Dcat\Admin\OperationLog;

use Dcat\Admin\Extend\Setting as Form;
use Dcat\Admin\OperationLog\Models\OperationLog;
use Dcat\Admin\Support\Helper;

class Setting extends Form
{
    public function title()
    {
        return $this->trans('log.title');
    }

    protected function formatInput(array $input)
    {
        $input['allowed_methods'] = Helper::array($input['allowed_methods']);

        return $input;
    }

    public function form()
    {
        $this->multipleSelect('allowed_methods')
            ->options(array_combine(OperationLog::$methods, OperationLog::$methods));
        $this->textarea('except')->placeholder("eg: /auth/operation-logs,/backup,")->help(',分隔 不需要admin/前缀');
        $this->textarea('secret_fields')->placeholder("eg: password,password_confirmation")->help(',分隔');
    }
}
