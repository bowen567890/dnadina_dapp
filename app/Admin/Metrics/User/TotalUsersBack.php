<?php

namespace App\Admin\Metrics\User;

use Carbon\Carbon;
use Dcat\Admin\Widgets\Metrics\Card;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Client;

class TotalUsersBack extends Card
{
    /**
     * 卡片底部内容.
     *
     * @var string|Renderable|\Closure
     */
    protected $footer;

    /**
     * 初始化卡片.
     */
    protected function init()
    {
        parent::init();
        $this->title('用户<span style="font-size:5px;">（总）</span>');
        $this->dropdown([
            '7'  => '相比最近7天',
            '30' => '相比最近30天',
        ]);
    }

    /**
     * 处理请求.
     *
     * @param Request $request
     *
     * @return void
     */
    public function handle(Request $request)
    {
        switch ($request->get('option')) {
            case '30':

                $nowVal  = User::query()->count();
                $sub30Val = User::query()->where('created_at','<',Carbon::now()->subDays(30)->endOfDay()->toDateTimeString())->count();
                $val = $this->comparedToNumber($nowVal,$sub30Val);
                $this->content($nowVal);
                if ($val > 0) {
                    $this->up($val);
                }
                if ($val < 0) {
                    $this->down(abs($val));
                }
                break;
            case '7':
            default:

                $nowVal  = User::query()->count();
                $sub7Val = User::query()->where('created_at','<',Carbon::now()->subDays(7)->endOfDay()->toDateTimeString())->count();

                $val     = $this->comparedToNumber($nowVal,$sub7Val);
                $this->content($nowVal);
                if ($val > 0) {
                    $this->up($val);
                }
                if ($val < 0) {
                    $this->down(abs($val));
                }
        }
    }

    /**
     * @param int $percent
     *
     * @return $this
     */
    public function up($percent)
    {
        return $this->footer(
            "<i class=\"feather icon-trending-up text-success\"></i> {$percent}% 增加"
        );
    }

    /**
     * @param int $percent
     *
     * @return $this
     */
    public function down($percent)
    {
        return $this->footer(
            "<i class=\"feather icon-trending-down text-danger\"></i> {$percent}% 减少"
        );
    }

    /**
     * 设置卡片底部内容.
     *
     * @param string|Renderable|\Closure $footer
     *
     * @return $this
     */
    public function footer($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * 渲染卡片内容.
     *
     * @return string
     */
    public function renderContent()
    {
        $content = parent::renderContent();
        $onlines = $this->onlines();

        return <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1">{$content} </h2>
</div>
<div class="ml-1 mt-1 font-weight-bold text-80">
    {$this->renderFooter()}
</div>
HTML;
    }

    /**
     * 渲染卡片底部内容.
     *
     * @return string
     */
    public function renderFooter()
    {
        return $this->toString($this->footer);
    }

    public function comparedToNumber($now,$last)
    {

        if (empty($now) && empty($last))
            return 0;

        if (!empty($now) && empty($last))
            return  100;

        return round(($now - $last) / $last * 100, 2);
    }


    public function onlines()
    {
        try {
            $socket_url  = Setting('socket_url');
            $httpClient  = new Client();
            $response    = $httpClient->request('get',$socket_url.'/api/onlines',['timeout' => 3]);
            $responseStr = $response->getBody()->getContents();
            $response = json_decode($responseStr);
            return $response->data->onlines;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
