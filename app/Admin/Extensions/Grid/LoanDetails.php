<?php

namespace App\Admin\Extensions\Grid;

use App\Admin\Renderable\LoanRepaymentTable;
use Dcat\Admin\Grid\Displayers\AbstractDisplayer;
use Dcat\Admin\Widgets\Modal;

class LoanDetails extends AbstractDisplayer
{
    public function display()
    {
        $RepaymentDetails = Modal::make()
            ->xl()
            ->title('期数详情')
            ->body(LoanRepaymentTable::make()->payload(['loan_id' => $this->value]))
            ->button('<button class="btn btn-outline-info sm-btn">期数详情</button>');
            
        return view('admin.loan-details-grid', ['model' => $RepaymentDetails]);
    }
}
