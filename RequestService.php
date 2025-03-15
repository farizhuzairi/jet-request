<?php

namespace Jet\Request;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;

abstract class RequestService
{
    use
    \Jet\Request\Client\Traits\Hostable,
    \Jet\Request\Client\Traits\UseTracer;

    protected array $data = [];
    protected string $method;
    protected string $accept;

    private PendingRequest|Response $response;
    private static string $_METHOD = "post";
    private static string $_ACCEPT = "application/json";
    
    public function __construct(
        array $data,
        ?string $method,
        ?string $accept
    )
    {
        $this->setProperties($data, $method, $accept);
        $this->setDefaultHeader();
        $this->setDefaultHostable();

        if(empty($this->method)) $this->method = static::$_METHOD;
        if(empty($this->accept)) $this->accept = static::$_ACCEPT;
    }

    private function setProperties(array $data, ?string $method, ?string $accept): void
    {
        if(! empty($data)) {
            $this->data = $data;
        }
        
        if(! empty($method)) {
            $this->method = $method;
        }

        if(! empty($accept)) {
            $this->accept = $accept;
        }
    }

    protected function invalidResponse(?Response $response = null): Response
    {
        if($response instanceof Response) {

            Log::error(
                "Resource error or server failure.",
                [
                    'request' => $this->getUrl(),
                    'method' => $this->getMethod(),
                    'data' => $this->getData(),
                    'error_details' => $response->collect()->only(['message', 'exception', 'file', 'line'])->toArray()
                ]
            );

        }

        if($response === null) {

            Log::error(
                "Bad Request. Http request error on internal server.",
                [
                    'request' => $this->getUrl(),
                    'method' => $this->getMethod(),
                    'data' => $this->getData(),
                    'error_details' => []
                ]
            );

            $jsonResponse = response()->json([
                'successful' => false,
                'statusCode' => 400,
                'message' => "Bad Request. Data not found.",
                'results' => []
            ], 400);

        }
        else {
            $jsonResponse = response()->json([
                'successful' => false,
                'statusCode' => 500,
                'message' => "There was a problem with the internal server.",
                'results' => []
            ], 500);
        }

        return new Response(
            new \GuzzleHttp\Psr7\Response(
                $jsonResponse->getStatusCode(),
                $jsonResponse->headers->all(),
                json_encode($jsonResponse->getData())
            ),
            Http::fake()
        );
    }

    public function data(array $data = []): static
    {
        if(! empty($data)) {
            $this->setData($data);
        }

        return $this;
    }

    public function setData(array $data): void
    {
        if(! empty($data)) {
            $this->data = $data;
        }
    }

    protected function getData(): array
    {
        return $this->data;
    }

    public function method(?string $method = null): static
    {
        if(! empty($method)) {
            $this->setMethod($method);
        }

        return $this;
    }

    public function setMethod(string $method): void
    {
        if(! empty($method)) {
            $this->method = $method;
        }
    }

    protected function getMethod(): string
    {
        return $this->method;
    }

    public function accept(?string $accept = null): static
    {
        if(! empty($accept)) {
            $this->setAccept($accept);
        }

        return $this;
    }

    public function setAccept(string $accept): void
    {
        if(! empty($accept)) {
            $this->accept = $accept;
        }
    }

    protected function getAccept(): string
    {
        return $this->accept;
    }

    final protected function api(?Closure $request): static
    {
        if($request instanceof Closure) {
            $request($this);
        }

        $response = Http::accept($this->getAccept());

        if($this->hasToken()) {
            $response->withToken($this->getToken());
        }
        
        $response->withHeaders($this->getHeader());
        // ->retry(3, 1000, throw: false);

        switch($this->getMethod()) {
            
            case "get";
            $response = $response->get($this->getUrl());
            break;
            
            case "post":
            $response = $response->post($this->getUrl(), $this->getData());
            break;

            default:
            $response = $this->invalidResponse();
            break;

        }

        if(! $response->collect()->has(['successful', 'statusCode', 'message', 'results'])) {
            $response = $this->invalidResponse($response);
        }

        $this->response = $response;
        return $this;
    }

    final public function response(): Response
    {
        return $this->response;
    }

    final public function result(): Collection
    {
        return $this->response()?->collect() ?? collect([]);
    }

    abstract public function getResponse(): Response;
    abstract public function getResult(): Collection;

    public function __call($name, $arguments)
    {
        try {
            $result = $this->response()->{$name}();
        } catch (\Throwable $e) {
            report($e->getMessage());
            $result = null;
        }

        return $result;
    }
}