<?php

namespace App\Admin\Extensions\Grid;

use Dcat\Admin\Grid\Displayers\AbstractDisplayer;

class Status extends AbstractDisplayer
{
    public function display()
    {
        if ($this->value == 0) {
            // 禁用，红色背景
            return "<span style='color: white; background-color: red; padding: 2px 8px;'>禁用</span>";
        } else {
            // 启用，绿色背景
            return "<span style='color: white; background-color: green; padding: 2px 8px;'>启用</span>";
        }
    }
}
