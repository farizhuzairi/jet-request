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

    'origin_data' => ['successful', 'statusCode', 'message', 'results']

];