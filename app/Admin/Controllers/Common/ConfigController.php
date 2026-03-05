<?php

namespace App\Admin\Controllers\Common;

use App\Models\Base\Language;
use App\Models\Base\Setting;
use App\Models\OrderProductModel;
use App\Models\UsersPower;
use App\Services\Common\ConfigService;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class ConfigController extends AdminController
{

    protected $title = "配置";

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Setting(), function (Grid $grid) {});
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $language = Language::query()->get();
        return Form::make(new Setting(), function (Form $form) use ($language) {

            $form->defaultEditingChecked();

            $form->tab("基础", function (Form $form) use ($language){

                $form->radio('default_lang', '默认语言')
                    ->options(Language::query()->where('status', true)->get()->pluck('name', 'slug'))
                    ->help('default_lang：当无法获取语言环境时将会使用此值');
                $form->text('money_decimal', '前端默认数字精度')->help('前端默认数字精度');

            })->tab("挖矿",function (Form $form){

                $form->decimal('power_price','每G算力单价(U)')->default(100)->required();
//                $form->text('lock_address','锁仓合约地址')->required();

                $form->rate('zhi_rate','直推奖励比例')->default(0)->required();
                $form->rate('ceng_rate','层级奖励比例')->default(0)->required();

            })->tab('提现手续费分润',function (Form $form){

                $form->rate('withdraw_market_rate','提现分润市场奖励比例')->default(0)->required();
                $form->rate('withdraw_node_rate','提现分润节点奖励比例')->default(0)->required();
                $form->rate('withdraw_education_rate','提现分润市场教育比例')->default(0)->required();


            })->tab("提现", function (Form $form) {
                $form->radio('usdt_withdraw_enable', '开启USDT提现功能')->options([1 => '开启', 0 => '关闭'])->help('usdt_withdraw_enable:关闭后用户将无法充值')
                    ->when(1, function (Form $form) {
                        $form->rate('usdt_withdraw_rate', 'USDT提现手续费率')->help('USDT提现手续费率');
                        $form->decimal('usdt_min_withdraw', '单笔最低提现USDT数量')->help('单笔最低提现USDT数量');
                        $form->decimal('usdt_max_withdraw', '单笔最高提现USDT数量')->help('单笔最高提现USDT数量');
                        $form->decimal('usdt_daily_max_withdraw', '单日最高提现USDT量')->help('单日最高提现USDT量');
                });

                $form->radio('fac_withdraw_enable', '开启FAC提现功能')->options([1 => '开启', 0 => '关闭'])->help('fac_withdraw_enable:关闭后用户将无法充值')
                    ->when(1, function (Form $form) {
                        $form->rate('fac_withdraw_rate', 'FAC提现手续费率')->help('FAC提现手续费率');
                        $form->decimal('fac_min_withdraw', '单笔最低提现FAC数量')->help('单笔最低提现FAC数量');
                        $form->decimal('fac_max_withdraw', '单笔最高提现FAC数量')->help('单笔最高提现FAC数量');
                        $form->decimal('fac_daily_max_withdraw', '单日最高提现FAC量')->help('单日最高提现FAC量');
                    });
            });


            $form->saved(function (Form $form) {
                ConfigService::getService()->update();
                return $form->response()->success('保存成功')->redirect('configs/1/edit');
            });


            $form->disableListButton();
            $form->disableDeleteButton();
            $form->disableCreatingCheck();
            $form->disableViewButton();
            $form->disableResetButton();
        });
    }
}
