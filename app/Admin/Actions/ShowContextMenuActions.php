<?php

namespace App\Admin\Actions;

use Dcat\Admin\Admin;

use Dcat\Admin\Grid\Displayers\DropdownActions;

//使用该类将显示列表后面的操作项按钮
class ShowContextMenuActions extends HiddenContextMenuActions {

    public function display($callback = null)
    {
        $this->addScript();
        return DropdownActions::display($callback);
    }


}
