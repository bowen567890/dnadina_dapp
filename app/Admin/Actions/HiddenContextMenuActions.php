<?php

namespace App\Admin\Actions;

use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Displayers\ContextMenuActions;

//使用该类将隐藏列表后面的操作项按钮，使用鼠标右键弹出来
class HiddenContextMenuActions extends ContextMenuActions{

    protected $elementId = 'grid-context-menu';

    //由于dcat本身bug,如果使用dcat自带的，跳转页面不会重新绑定右键单击事件，需要优化版本
    protected function addScript(){
        $script = <<<JS
        (function () {
            var id = '#{$this->elementId}';

    // 绑定右键菜单的事件
    function bindContextMenu() {
        $("body").on("contextmenu", "#{$this->grid->getTableId()} tr", function(e) {
            var menu = $(this).find('td .grid-dropdown-actions .dropdown-menu');
            var index = $(this).index();

            // 如果有对应的菜单，移到新的位置
            if (menu.length) {
                menu.attr('index', index).detach().appendTo(id);
            } else {
                menu = $(id + ' .dropdown-menu[index='+index+']');
            }

            // 调整菜单的位置，避免超出视口
            if (menu.height() > (document.body.clientHeight - e.pageY)) {
                menu.css({left: e.pageX+10, top: e.pageY - menu.height()}).show();
            } else {
                menu.css({left: e.pageX+10, top: e.pageY-10}).show();
            }
            return false;
        });
    }

    // 每次页面加载时调用绑定函数，确保菜单重新初始化
    $(id + ' .dropdown-menu').remove();
    bindContextMenu();


    // 如果菜单容器不存在，创建它
    if (! $(id).length) {
        $("body").append('<div id="{$this->elementId}" class="dropdown" style="display: contents"></div>');
    }


    $(document).on('click',function(){
        $(id + ' .dropdown-menu').hide();
    })

    $(id).click('a', function () {
        $(this).find('.dropdown-menu').hide();
    });

        })();
JS;

        Admin::script($script);
    }

    public function display($callback = null)
    {
        $this->addScript();

        Admin::style('.grid__actions__ .dropdown{display: none!important;} th.grid__actions__{display: none!important;} .grid__actions__{width:1px}');

        return parent::display($callback);
    }


}
