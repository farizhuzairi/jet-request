<?php

namespace Jet\Request;

use Jet\Request\RequestService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client extends RequestService
{
    /**
     * Client Request Object
     * 
     */
    protected Response $httpRequest;

    /**
     * Client Request Construction
     * ---
     */
    public function __construct(
        array|string $data,
        string $method,
        string $accept
    )
    {
        parent::__construct(data: $data, method: $method, accept: $accept);
    }

    /**
     * Create New Request
     * 
     */
    public function api(): static
    {
        $this->httpRequest = Http::accept($this->accept)
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
        ]);

        return $this;
    }

    /**
     * Create New Request Static
     * 
     */
    public static function request(array|string $data = [], string $method = 'post', string $accept = "application/json"): static
    {
        $object = new self($data, $method, $accept);
        $object->api();
        
        return $this;
    }
}