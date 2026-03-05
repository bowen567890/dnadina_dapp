<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();
Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('auth/login', 'AuthController@getLogin');
    $router->post('auth/login', 'AuthController@postLogin');
    $router->get('auth/captcha', 'AuthController@captcha');


    //语言设置
    $router->resource('language', 'LanguageController');
    $router->resource('main_currency', 'MainCurrencyController');
    $router->resource('rank_config', 'RankConfigController');
    $router->resource('depth_config', 'DepthConfigController');
    $router->resource('fund_pool', 'FundPoolController');
    $router->resource('website_analyze_daily', 'WebsiteAnalyzeDailyController');
    $router->resource('dht_lock_order', 'DhtLockOrderController');

    //公共
    $router->namespace('Common')->group(function ($router) {
        $router->resource('configs', \App\Admin\Controllers\Common\ConfigController::class);//系统配置
    });


    //CMS
    $router->namespace('Cms')->group(function ($router) {
        $router->resource('cms-news', \App\Admin\Controllers\Cms\NewsController::class);
        $router->resource('cms-notice', \App\Admin\Controllers\Cms\NoticeController::class);
        $router->resource('cms-languages', \App\Admin\Controllers\Cms\LanguageController::class);
        $router->resource('banner', \App\Admin\Controllers\Cms\BannerController::class);
    });
    
    //节点
    $router->namespace('Node')->group(function ($router) {
        $router->resource('node_config', \App\Admin\Controllers\Node\NodeConfigController::class);
        $router->resource('node_order', \App\Admin\Controllers\Node\NodeOrderController::class);
        $router->resource('node_period', \App\Admin\Controllers\Node\NodePeriodController::class);
    });

    //用户相关
    $router->namespace('User')->group(function ($router) {
        //用户列表
        $router->resource('users-list', \App\Admin\Controllers\User\UserController::class);
        //推荐树
        $router->resource('users-tree', \App\Admin\Controllers\User\UserTreeController::class);
        
        $router->resource('user_dht', \App\Admin\Controllers\User\UserDhtController::class);
        $router->resource('user_dht_lock', \App\Admin\Controllers\User\UserDhtLockController::class);
        $router->resource('user_usdt', \App\Admin\Controllers\User\UserUsdtController::class);
    });

    $router->namespace('Withdraw')->group(function ($router) {
        $router->resource('withdraw', \App\Admin\Controllers\Withdraw\WithdrawController::class);
    });

    $router->get('/', 'HomeController@index');
});


//判断如果是线上环境，需要关闭扩展模块的后台管理，否则容易造成上传漏洞，切记,所以上线一定要将.env的APP_ENV改成production
if (app()->environment('production')) {
    Route::group([
        'domain' => config('admin.route.domain'),
        'prefix' => config('admin.route.prefix'),
        'namespace' => config('admin.route.namespace'),
        'middleware' => config('admin.route.middleware'),
    ], function (Router $router) {
        Route::any('/auth/extensions', function () {
            abort(404, 'Not Found'); // 返回 404 错误
        });
    });
}
