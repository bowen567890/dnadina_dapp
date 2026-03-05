<?php
return [

    //白名单路由地址
    'uncrypted_routs'=> [
        'api/4cf4c22f6e6ce089937de71339f1b87d',
        'api/v1/wallRechargeCallback',
        'api/v1/wallWithdrawCallback',
        'api/aaa',
        'api/price'
    ],
    //通行令牌
    'passToken'=> 'cng5DB3brXHLhDYOnyMcE9r0oNT9wp',
    
    'APP_URL' => env('APP_URL'),
    'CHECK_LAST_TOKEN' => env('CHECK_LAST_TOKEN', false),
    'USDT_ADDRESS' => env('USDT_ADDRESS'),
    'WBNB_ADDRESS' => env('WBNB_ADDRESS'),
    'WBNB_ADDRESS_LP' => env('WBNB_ADDRESS_LP'),
    'BTC_ADDRESS' => env('BTC_ADDRESS'),
    'BTC_ADDRESS_LP' => env('BTC_ADDRESS_LP'),
    'BUSD_ADDRESS' => env('BUSD_ADDRESS'),
    'RWA_ADDRESS' => env('RWA_ADDRESS', ''),
    'RWA_ADDRESS_LP' => env('RWA_ADDRESS_LP', ''),
    
    'WNADI_ADDRESS' => env('WNADI_ADDRESS'),
    'WNADI_ADDRESS_LP' => env('WNADI_ADDRESS_LP'),
    'DHT_ADDRESS' => env('DHT_ADDRESS'),
    'DHT_ADDRESS_LP' => env('DHT_ADDRESS_LP'),
    
    'NADI_LENDING_API' => env('NADI_LENDING_API'),
    'ON_CHAIN_PAY' => env('ON_CHAIN_PAY'),
    'CHECK_SIGN_MESSAGE' => env('CHECK_SIGN_MESSAGE', true),
];
