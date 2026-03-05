<?php

namespace App\Admin\Actions\Grid;


use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;

class DeliveryOrder extends RowAction
{
    /**
     * @return string
     */
	protected $title = '商品发货';



	public function render()
    {

        // 实例化表单类并传递自定义参数
        $form = \App\Admin\Forms\DeliveryOrderForm::make()->payload(['id' => $this->getKey()]);

        return Modal::make()->lg()->title($this->title)->body($form)->button($this->title);
    }

}
