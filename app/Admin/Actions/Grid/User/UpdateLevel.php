<?php
namespace App\Admin\Actions\Grid\User;

use App\Admin\Forms\User\UpdateLevelForm;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;


class UpdateLevel extends RowAction
{

    protected $title = '调整等级';

    public function __construct(protected int $rid)
    {

        parent::__construct($this->title);
    }

    public function render()
    {

        $form = UpdateLevelForm::make()->payload(['rid'=>$this->rid]);
        $title = "[更新等级]UID:(".$this->rid.")";
        return Modal::make()->lg()->body($form)->title($title)->button($this->title);
    }
}
