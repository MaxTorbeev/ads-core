<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Web service connection configs
     |--------------------------------------------------------------------------
     */
    'wsdl-client' => [
        'url' => env('WS_URL'),
        'user' => env('WS_USER'),
        'password' => env('WS_PASSWORD'),
    ],
];
