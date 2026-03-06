<?php

use App\Http\Controllers\Api\GatewayController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Common\CallbackController;
use App\Http\Controllers\Api\V1\Common\NoticeController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Support\Facades\Route;

// 接口统一访问网关
Route::post('/4cf4c22f6e6ce089937de71339f1b87d', [GatewayController::class, 'accept']);

Route::post('/decrypt', [GatewayController::class, 'decrypt']);
Route::post('/encrypt', [GatewayController::class, 'encrypt']);

Route::post('/aaa', [GatewayController::class, 'aaa']);
Route::post('/getBtcPrice1', [GatewayController::class, 'getBtcPrice1']);
Route::post('/getBtcPrice2', [GatewayController::class, 'getBtcPrice2']);
Route::post('/getBtcPrice3', [GatewayController::class, 'getBtcPrice3']);
Route::post('/getLpInfo', [GatewayController::class, 'getLpInfo']);
Route::post('/getLpInfov3', [GatewayController::class, 'getLpInfov3']);

// 业务服务
Route::prefix('v1')->middleware(['api', 'cors-should'])->group(function () {
    // 认证相关路由
    Route::prefix('auth')->group(function () {
        //获取随机签名字符串
        Route::post('loginMessage', [LoginController::class, 'loginMessage']);
        // 登录or注册账户
        Route::post('login', [LoginController::class, 'login']);
        //检测是否注册
        Route::post('isRegister', [LoginController::class, 'isRegister']);
    });

    //节点配置
    Route::prefix('node')->group(function () {
        //获取节点配置
        Route::get('config', [\App\Http\Controllers\Api\V1\User\NodeController::class, 'config']);
    });


    // 基础相关接口
    Route::prefix('common')->group(function(){
        Route::post('config', [\App\Http\Controllers\Api\V1\Common\ConfigController::class,'get']);//获取配置
        Route::post('lang', [\App\Http\Controllers\Api\V1\Common\LangController::class,'get']);//获取语言配置
//        Route::get('bannerList',[\App\Http\Controllers\Api\V1\Banner\BannerController::class,'bannerList']);
        Route::post('marquee', [NoticeController::class, 'marquee']);
//        Route::get('priceLog', [\App\Http\Controllers\Api\V1\Common\PriceLogController::class,'priceLog']);
        //首页相关公开信息
//        Route::get('mainInfo',[\App\Http\Controllers\Api\V1\Common\MainPageController::class, 'info']);
    });
    Route::prefix('index')->group(function (){
        Route::post('index',[\App\Http\Controllers\Api\V1\IndexController::class, 'index']);
        Route::post('tokenList',[\App\Http\Controllers\Api\V1\IndexController::class, 'tokenList']);
    });

    // 需要登录鉴权的接口
    Route::middleware(['auth:api','check-status'])->group(function () {
        
//         Route::prefix('index')->group(function (){
//             Route::post('index',[\App\Http\Controllers\Api\V1\IndexController::class, 'index']);
//         });
        
        // 用户相关路由
        Route::prefix('user')->group(function () {
            //个人信息
            Route::post('info', [\App\Http\Controllers\Api\V1\User\UserController::class, 'info']);
            Route::post('zhiList', [\App\Http\Controllers\Api\V1\User\UserController::class, 'zhiList']);
            Route::post('teamList', [\App\Http\Controllers\Api\V1\User\UserController::class, 'teamList']);
            Route::post('usdtLog', [\App\Http\Controllers\Api\V1\User\UserController::class, 'usdtLog']);
            Route::post('dhtLog', [\App\Http\Controllers\Api\V1\User\UserController::class, 'dhtLog']);
            Route::post('dhtLockLog', [\App\Http\Controllers\Api\V1\User\UserController::class, 'dhtLockLog']);
        });

        Route::prefix('node')->group(function () {
            Route::post('config', [\App\Http\Controllers\Api\V1\Node\NodeController::class, 'config']);
            Route::post('buy', [\App\Http\Controllers\Api\V1\Node\NodeController::class, 'buy']);
            Route::post('buyList',[\App\Http\Controllers\Api\V1\Node\NodeController::class, 'buyList']);
            Route::post('lockOrder',[\App\Http\Controllers\Api\V1\Node\NodeController::class, 'lockOrder']);
        });
        
        Route::prefix('withdraw')->group(function () {
            Route::post('index', [\App\Http\Controllers\Api\V1\Withdraw\WithdrawController::class, 'index']);
            Route::post('list', [\App\Http\Controllers\Api\V1\Withdraw\WithdrawController::class, 'list']);
        });

        //公告
        Route::prefix('notice')->group(function () {
            Route::post('marquee', [NoticeController::class, 'marquee']);
            Route::post('detail', [NoticeController::class, 'detail']);
        });
    });

    Route::middleware(['check-callback'])->group(function () {
        //钱包充值回调
        Route::post('wallRechargeCallback', [CallbackController::class, 'wallRechargeCallback'])->name('wallRechargeCallback');
        //钱包提现回调
        Route::post('wallWithdrawCallback', [CallbackController::class, 'wallWithdrawCallback'])->name('wallWithdrawCallback');
    });


});
