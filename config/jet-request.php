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
        'data_meta' => [
            'object' => 'data_meta',
            'contents' => [
                'data',
                'links',
                'meta'
            ],
            'class' => \Jet\Request\Client\Factory\Response\DataMeta::class,
            'additional' => null,
        ],
        'data_results' => [
            'object' => 'data_results',
            'contents' => [
                'successful',
                'statusCode',
                'message',
                'results'
            ],
            'class' => \Jet\Request\Client\Factory\Response\DataResults::class,
            'additional' => null,
        ],
    ]

];