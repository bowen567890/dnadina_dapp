<?php

namespace App\Admin\Extensions\Grid;

use Dcat\Admin\Grid\Displayers\AbstractDisplayer;

class Percent extends AbstractDisplayer
{
    public function display()
    {
       // sprintf("%.2f",($progressData['this_h']/$progressData['total_hours']))
        return sprintf("%.2f",($this->value)). '%';
    }
}
