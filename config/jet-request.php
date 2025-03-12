<?php

return [

    'api' => [
        'http' => env('HOST_API_HTTP', 'http://'),
        'host' => env('HOST_API_DOMAIN', ''),
        'endpoint' => env('HOST_API_ENDPOINT', ''),
        'version' => env('HOST_API_VERSION', ''),
    ],

    'token' => env('AUTH_TOKEN', null),

    'token_service' => env("TOKEN_SERVICE", \Jet\Request\Client\Keys::class),

    // 'ems' => false

];