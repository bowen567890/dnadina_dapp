<?php
namespace App\Admin\Actions\Grid\Withdraw;



use App\Admin\Forms\Withdraw\AuditForm;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;


class Audit extends RowAction
{

    protected $title = '审核';

    public function __construct(protected int $rid)
    {
        parent::__construct($this->title);
    }

    public function render()
    {
        $form = AuditForm::make()->payload(['rid'=>$this->rid]);
        return Modal::make()->body($form)->title('提现审核')->button($this->title);
    }
}
