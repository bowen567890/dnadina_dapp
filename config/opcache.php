<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OPcache Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for OPcache management
    |
    */

    'url' => env('OPCACHE_URL', config('app.url')),
    'prefix' => 'opcache-api',
    'verify' => true,
    'headers' => [],
    
    /*
    |--------------------------------------------------------------------------
    | Directories to Monitor
    |--------------------------------------------------------------------------
    |
    | Directories that should be monitored for OPcache operations
    |
    */
    'directories' => [
        base_path('app'),
        base_path('bootstrap'),
        base_path('public'),
        base_path('resources'),
        base_path('routes'),
        base_path('storage'),
        base_path('vendor'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Exclusion Patterns
    |--------------------------------------------------------------------------
    |
    | Patterns to exclude from OPcache operations
    |
    */
    'exclude' => [
        'test',
        'Test',
        'tests',
        'Tests',
        'stub',
        'Stub',
        'stubs',
        'Stubs',
        'dumper',
        'Dumper',
        'Autoload',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Security settings for OPcache clear endpoint
    |
    */
    'security' => [
        'enabled' => env('OPCACHE_SECURITY_ENABLED', true),
        'secret_key' => env('OPCACHE_SECRET_KEY', 'opcache_clear'),
        'allowed_ips' => [
            '127.0.0.1',
            '::1',
            'localhost'
        ],
        'allow_local_networks' => true,
    ],
];
