<?php

namespace Jet\Request;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

abstract class RequestService
{
    /**
     * Client Request Object
     * 
     */
    protected static PendingRequest|Response $response;

    /**
     * Default Http Method
     * 
     */
    protected static string $_METHOD = "post";

    /**
     * Default Http Accept
     * 
     */
    protected static string $_ACCEPT = "application/json";
    
    use
    \Jet\Request\Client\Traits\Hostable,
    \Jet\Request\Client\Traits\Keyable,
    \Jet\Request\Client\Traits\UseTracer;

    /**
     * Construction
     * ---
     */
    public function __construct(
        protected array|string $data,
        protected string $method,
        protected string $accept
    )
    {
        $this->setHeader();
        if(! isset(static::$response)) $this->api();
    }

    /**
     * Set Http Method
     * 
     */
    public function method(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Set Data Form
     * 
     */
    public function data(array|string $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set Accept
     * 
     */
    public function accept(string $accept): static
    {
        $this->accept = $accept;
        return $this;
    }

    /**
     * Send Request
     * 
     */
    public function send(): Response
    {
        if(! static::$response instanceof Response) {
            throw new \Exception("Error Processing Request: Invalid data object request.");
        }

        return static::$response;
    }

    /**
     * Create New Request
     * From client
     * 
     */
    public function api(): static
    {
        $request = Http::accept($this->accept);

        // with token
        if($this->hasToken()) {
            $request->withToken($this->getToken());
        }
        
        $request->withHeaders($this->getHeader())
        ->retry(3, 1000, throw: false);

        static::$response = $request->withUrlParameters([
            'url' => 'http://haschanetwork.local',
            'endpoint' => 'ems',
            'version' => '1',
            'topic' => 'ping/test',
        ])
        ->post('{+url}/{endpoint}/{version}/{+topic}', [
            'name' => 'Sara',
            'role' => 'Privacy Consultant',
        ]);

        // static::$response = $request;
        return $this;
    }
}