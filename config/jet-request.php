<?php

return [

    'api' => [
        'http' => env('HOST_API_HTTP', 'https://'),
        'host' => env('HOST_API_DOMAIN', 'haschanetwork.net'),
        'endpoint' => env('HOST_API_ENDPOINT', 'ems'),
        'version' => env('HOST_API_VERSION', '1'),
    ],

    'token' => env('AUTH_TOKEN', null),

    'token_service' => env("TOKEN_SERVICE", null),

    'data_wrapper' => 'data_meta',

    'wrappers' => [
        'data' => [
            'contents' => [
                'data'
            ],
            'object' => \Jet\Request\Client\Factory\Response\Data::class,
            'additional' => null,
        ],
        'data_meta' => [
            'contents' => [
                'data',
                'meta'
            ],
            'object' => \Jet\Request\Client\Factory\Response\DataMeta::class,
            'additional' => null,
        ],
        'data_results' => [
            'contents' => [
                'successful',
                'statusCode',
                'message',
                'results'
            ],
            'object' => \Jet\Request\Client\Factory\Response\Results::class,
            'additional' => null,
        ],
    ]

];