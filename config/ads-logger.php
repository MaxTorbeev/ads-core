<?php

use Ads\Logger\Services\Logger\DefaultLogger;

return [
    /*
    |--------------------------------------------------------------------------
    | Logger configs
    |--------------------------------------------------------------------------
    */
    'driver' => DefaultLogger::class,

    'max_length' => 1024,

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
        'fields_exclusion' => [
            'broadcasting/auth' => false,
            'logout' => false,
        ],
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
