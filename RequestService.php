<?php

namespace Jet\Request;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;

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
        protected ?string $method,
        protected ?string $accept
    )
    {
        $this->defaultHeader();
        $this->defaultHostable();

        if(empty($method)) $this->method = static::$_METHOD;
        if(empty($accept)) $this->accept = static::$_ACCEPT;

        if(! isset(static::$response)) $this->api();
    }

    public function data(array|string $data): static
    {
        $this->data = $data;
        return $this;
    }

    protected function getData(): array
    {
        return $this->data;
    }

    public function method(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    protected function getMethod(): string
    {
        return $this->method;
    }

    public function accept(string $accept): static
    {
        $this->accept = $accept;
        return $this;
    }

    /**
     * Create Data Request
     * 
     */
    protected function dataProcess(PendingRequest $request, string $method, string $url, array $data): Response
    {
        $result = null;

        try {

            if($method === 'post') {
                $result = $request->post($url, $data);
            }

        } catch (RequestException $e) {
            report($e->getMessage());
            $result = $this->invalidResponse();
        }

        return $result;
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

        static::$response = $this->dataProcess($request, $this->getMethod(), $this->getUrl(), $this->getData());
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
     * Manual Response
     * 
     */
    protected function invalidResponse(): Response|JsonResponse
    {
        return response()->json([
            'successful' => false,
            'statusCode' => 500,
            'message' => "There was a problem with the internal server.",
            'results' => []
        ])
        ->setStatusCode(500);
    }
}