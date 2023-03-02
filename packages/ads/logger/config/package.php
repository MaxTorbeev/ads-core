<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Logger configs
    |--------------------------------------------------------------------------
    */
    'driver' => \Ads\Logger\Services\Logger\DefaultHttpLogger::class,

    'logging' => [
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
