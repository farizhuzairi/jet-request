<?php

return [

    /**
     * Host API Connection
     * 
     */
    'api' => [
        'http' => env('HOST_API_HTTP', 'http://'),
        'host' => env('HOST_API_DOMAIN', 'haschanetwork.local'),
        'endpoint' => env('HOST_API_ENDPOINT', ''),
        'version' => env('HOST_API_VERSION', ''),
    ],

];