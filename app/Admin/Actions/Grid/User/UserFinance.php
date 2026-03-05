<?php
namespace App\Admin\Actions\Grid\User;

use App\Admin\Forms\User\UpdateLevelForm;
use App\Admin\Forms\User\UserFinanceForm;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;


class UserFinance extends RowAction
{

    protected $title = '调整余额';

    public function __construct(protected int $rid)
    {

        parent::__construct($this->title);
    }

    public function render()
    {

        $form = UserFinanceForm::make()->payload(['rid'=>$this->rid]);
        $title = "[调整余额]UID:(".$this->rid.")";
        return Modal::make()->lg()->body($form)->title($title)->button($this->title);
    }
}
