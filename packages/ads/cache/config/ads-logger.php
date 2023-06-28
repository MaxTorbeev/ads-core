<?php

use Ads\Logger\Services\Logger\DefaultHttpLogger;

return [
    /*
    |--------------------------------------------------------------------------
    | Logger configs
    |--------------------------------------------------------------------------
    */
    'driver' => DefaultHttpLogger::class,

    'max_length' => 1024,

    'success_store_days' => env('ADS_LOGGER_SUCCESS_STORE_DAYS', 14),

    'error_store_days' => env('ADS_LOGGER_ERROR_STORE_DAYS', 14),

    'except' => [
        /*
        |--------------------------------------------------------------------------
        | Исключение информации из полей при записи в лог
        |--------------------------------------------------------------------------
        | 'route.name || uri' => [
        |       'response' => [
        |           'Fields.path.with.dots'
        |       ],
        |       'request' => [
        |           'Confidence.Document'
        |       ],
        |   ]
        | 'broadcasting/auth' => false, // Логирование полностью отключено для этого URI
        */
        'user.show' => [
            'request' => [
                'password',
            ],
            'response' => [
                'password',
            ],
        ],
        'user.store' => [
            'request' => [
                'password',
                'passwordConfirm'
            ],
        ],
        'users.change.password.profile' => [
            'request' => [
                'password',
            ],
        ],
        'users.change.password' => [
            'request' => [
                'password',
            ],
        ],
    ]
];
