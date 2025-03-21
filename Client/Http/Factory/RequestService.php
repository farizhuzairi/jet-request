<?php

namespace Jet\Request\Client\Http\Factory;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Jet\Request\Client\Contracts\Requestionable;

class RequestService implements Requestionable
{
    use
    \Jet\Request\Client\Traits\Hostable,
    \Jet\Request\Client\Traits\UseTracer,
    \Jet\Request\Client\Supports\UseInvalidResponse;

    protected array $data = [];
    protected string $method;
    protected string $accept;

    private PendingRequest|Response $response;
    private static string $_METHOD = "post";
    private static string $_ACCEPT = "application/json";

    protected bool $successful = false;
    protected int $statusCode = 0;
    protected ?string $message = null;

    protected static array $_ORIGIN_DATA_RESULTS = [];
    
    public function __construct(
        array $data,
        ?string $method,
        ?string $accept
    )
    {
        static::$_ORIGIN_DATA_RESULTS = config('jet-request.origin_data');

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

    public function getData(): array
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

    public function getMethod(): string
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

    public function getAccept(): string
    {
        return $this->accept;
    }

    public function api(?Closure $request): static
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

        if(! $response->collect()->has(static::$_ORIGIN_DATA_RESULTS)) {
            $response = $this->invalidResponse($response);
        }

        $this->assetable($response);
        return $this;
    }

    protected function assetable($response): void
    {
        $this->response = $response;

        $data = $response->collect();
        $this->successful = $data['successful'];
        $this->statusCode = $data['statusCode'];
        $this->message = $data['message'];
    }

    public function response(): Response
    {
        return $this->response;
    }

    public function results(): array
    {
        $data = $this->response()->collect();

        if($data instanceof Collection) {
            return $data->get('results') ?? [];
        }

        return [];
    }

    public function successful(): bool
    {
        return $this->successful;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function message(): ?string
    {
        return $this->message;
    }

    public function getOriginalResponse(): array
    {
        return $this->response()->collect()->only(static::$_ORIGIN_DATA_RESULTS)->all();
    }

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

    public function getResponse(): Response
    {
        return $this->response();
    }

    public function getResults(): array
    {
        return $this->results();
    }

    public function getSuccessful(): bool
    {
        return $this->successful();
    }

    public function getStatusCode(): int
    {
        return $this->statusCode();
    }

    public function getMessage(): ?string
    {
        return $this->message();
    }
}