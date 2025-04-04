<?php

return [

    'api' => [
        'http' => env('HOST_API_HTTP', 'https://'),
        'host' => env('HOST_API_DOMAIN', ''),
        'endpoint' => env('HOST_API_ENDPOINT', ''),
        'version' => env('HOST_API_VERSION', ''),
    ],

    'token' => env('AUTH_TOKEN', null),

    'token_service' => env("TOKEN_SERVICE", null),

    'data_wrapper' => 'data_meta',

    'wrappers' => [
        'data_meta' => [
            'object' => 'data_meta',
            'contents' => [
                'data',
                'author',
                'meta'
            ],
            'class' => \Jet\Request\Client\Http\Factory\Response\DataMeta::class,
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
            'class' => \Jet\Request\Client\Http\Factory\Response\DataResults::class,
            'additional' => null,
        ],
    ],

    'data_result_response' => ['results', 'author', 'meta', 'successful', 'statusCode', 'message'],

];