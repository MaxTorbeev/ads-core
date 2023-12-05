<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Logger configs
    |--------------------------------------------------------------------------
    */
    'driver' => \Ads\Logger\Services\Logger\DefaultHttpLogger::class,

    'success_store_days' => env('ADS_LOGGER_SUCCESS_STORE_DAYS', 14),

    'error_store_days' => env('ADS_LOGGER_ERROR_STORE_DAYS', 14),

    // Перечисленные HTTP статусы не будут записаны в лог
    'except_code_statuses' => [404, 403, 401],

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
        |       request' => false, // Не записывать в логи входящие данные
        |       response' => false, // Не записывать в логи исходящие данные
        |   ]
        | 'broadcasting/auth' => false, // Логирование полностью отключено для этого URI
        | 'route.name' => [
        |       'onlyErrors' => true // Лог будет записан только в случае ошибки
        |  ]
        */
        'login' => [
            'onlyErrors' => true
        ],
        'logout' => false,
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
