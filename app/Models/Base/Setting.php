<?php

namespace App\Models\Base;


use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Setting extends Model
{
    use Cachable;
    const CACHE_TAG = "app::config";
    public $timestamps = false;

    protected $casts = [
        'order_product' => 'json',


        'hosting_open' => 'boolean',
        'open_order_range' => 'boolean',

        'price_config'=>'json',
    ];

    public function currency()
    {
        $config = $this->first();
        return [
            'default_currency' => $config->default_currency,
            'fiat_code'=> $config->fiat_code,
            'rmb_money_rate'=> $config->rmb_money_rate,
        ];
    }

    protected static function booted()
    {
        //拦截删除
        static::deleting(function ($item) {
            abort(400, "无法删除");
        });
    }

}
