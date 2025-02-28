<?php

namespace Jet\Request;

use Jet\Request\Client\RequestService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client extends RequestService
{
    /**
     * Client Request Construction
     * ---
     */
    public function __construct(
        string $method = 'post',
        array|string $data = [],
        string $accept = "application/json"
    )
    {
        parent::__construct(method: $method, data: $data, accept: $accept);
    }

    /**
     * Create New Request
     * 
     */
    public function api(): Response
    {
        return Http::accept($this->accept)
        ->withToken('token_xxx')
        ->withHeaders([
            'User-Agent' => 'EMS/1.0.0 Base/06846.347 ' . config('app.name'),
            'App-ID' => '123',
            'Request-ID' => '123',
            'Userable-Key' => '123',
            'License-Key' => '123',
            'Visit' => '123',
        ])
        ->retry(3, 1000, throw: false)
        ->withUrlParameters([
            'url' => 'http://haschanetwork.local',
            'endpoint' => 'ems',
            'version' => '1',
            'topic' => 'ping/test',
        ])
        ->post('{+url}/{endpoint}/{version}/{+topic}', [
            'name' => 'Sara',
            'role' => 'Privacy Consultant',
        ])
        ;
    }
}