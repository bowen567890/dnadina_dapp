<?php

namespace App\Admin\Metrics\User;

use App\Admin\Metrics\Level\Request;
use App\Models\UsersPower;
use App\Models\UsersPowerLog;
use Dcat\Admin\Admin;
use Illuminate\Support\Carbon;
use Dcat\Admin\Widgets\Metrics\Card;
use App\Models\User;

class UserLevelPower extends Card
{
    protected $labels = ['T1算力', 'T2算力','T3算力','T4算力','T5算力','T6算力','T7算力','T8算力','T9算力','T10算力'];

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('等级算力');
    }


    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle($request)
    {
        $powers = [];
        for ($i = 1; $i <= 10; $i++) {
            $powers[] = UsersPower::query()
                ->join('users', 'users.id', '=', 'users_power.user_id')
                ->where('users.level_id', $i)
                ->sum('users_power.valid_power');
        }
        
        switch ($request->get('option')) {
            default:
                $this->withContent(...$powers);
        }
    }

    /**
     * 设置卡片头部内容.
     *
     * @param mixed ...$powers
     * @return $this
     */
    protected function withContent(...$powers)
    {
        $html = '<div class="row" style="margin: 0 18px;">';
        
        // 第一列 (1-5)
        $html .= '<div class="col-6" style="padding: 0 5px;">';
        for ($i = 0; $i < 5; $i++) {
            $color = $i === 1 ? 'color: red' : '';
            $html .= <<<HTML
            <div class="d-flex justify-content-between align-items-center mb-0">
                <div class="d-flex align-items-center">
                    <i class="fa fa-circle mr-1" style="color: {$this->getColor($i)}; font-size: 14px;"></i>
                    <span style="font-size: 14px; {$color}">{$this->labels[$i]}</span>
                </div>
                <span class="font-weight-bold" style="font-size: 14px; {$color}">{$powers[$i]}</span>
            </div>
HTML;
        }
        $html .= '</div>';

        // 第二列 (6-10)
        $html .= '<div class="col-6" style="padding: 0 5px;">';
        for ($i = 5; $i < 10; $i++) {
            $html .= <<<HTML
            <div class="d-flex justify-content-between align-items-center mb-0">
                <div class="d-flex align-items-center">
                    <i class="fa fa-circle mr-1" style="color: {$this->getColor($i)}; font-size: 14px;"></i>
                    <span style="font-size: 14px;">{$this->labels[$i]}</span>
                </div>
                <span class="font-weight-bold" style="font-size: 14px;">{$powers[$i]}</span>
            </div>
HTML;
        }
        $html .= '</div>';
        
        $html .= '</div>';

        return $this->content($html);
    }

    /**
     * 获取颜色
     * @param int $index
     * @return string
     */
    protected function getColor($index)
    {
        $colors = [
            '#007bff', // 蓝色
            '#FFD700', // 金色
            '#CD9B9B', // 棕色
            '#28a745', // 绿色
            '#dc3545', // 红色
            '#6f42c1', // 紫色
            '#fd7e14', // 橙色
            '#20c997', // 青色
            '#17a2b8', // 浅蓝色
            '#6c757d'  // 灰色
        ];
        
        return $colors[$index] ?? '#007bff';
    }
}
