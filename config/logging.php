<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
            'replace_placeholders' => true,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
            'facility' => LOG_USER,
            'replace_placeholders' => true,
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        //服务日志
        'server' => [
            'driver' => 'daily',
            'path' => storage_path('logs/server.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        //邮件日志
        'mail' => [
            'driver' => 'daily',
            'path' => storage_path('logs/mail.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        //充值回调日志
        'recharge_callback' => [
            'driver' => 'daily',
            'path' => storage_path('logs/recharge_callback.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        'withdraw' => [
            'driver' => 'daily',
            'path' => storage_path('logs/withdraw.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        //提现回调日志
        'withdraw_callback' => [
            'driver' => 'daily',
            'path' => storage_path('logs/withdraw_callback.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        //提现推送日志
        'withdraw_push' => [
            'driver' => 'daily',
            'path' => storage_path('logs/withdraw_push.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'web3_signature' => [
            'driver' => 'daily',
            'path' => storage_path('logs/web3_signature.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],


        'settlement' => [
            'driver' => 'daily',
            'path' => storage_path('logs/settlement.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'release_token' => [
            'driver' => 'daily',
            'path' => storage_path('logs/release_token.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        'buy_node' => [
            'driver' => 'daily',
            'path' => storage_path('logs/buy_node.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        'command_log' => [
            'driver' => 'daily',
            'path' => storage_path('logs/command_log.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        'lp_info' => [
            'driver' => 'daily',
            'path' => storage_path('logs/lp_info.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        'ave_price' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ave_price.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        'buy_product' => [
            'driver' => 'daily',
            'path' => storage_path('logs/buy_product.log'),
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
    ],

];
