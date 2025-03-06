<?php

namespace Jet\Request;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Jet\Request\Client\Contracts\Requestionable;

abstract class RequestService
{
    protected static PendingRequest|Response $response;
    protected static string $_METHOD = "post";
    protected static string $_ACCEPT = "application/json";
    
    use
    \Jet\Request\Client\Traits\Hostable,
    \Jet\Request\Client\Traits\UseTracer;

    protected array $data = [];
    protected string $method;
    protected string $accept;

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

        if(! isset(static::$response)) $this->api();
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

    protected function hasResponse(): bool
    {
        try {
            if(static::$response instanceof Response) {
                return true;
            }
            throw new \Exception("Error Processing Request: Invalid data object request.");
        } catch (RequestException $e) {
            report($e->getMessage());
        }

        return false;
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

    final protected function dataProcess(PendingRequest $request, string $method, string $url, array $data): Response
    {
        $result = null;

        try {

            switch($method) {
                case "get";
                $result = $request->get($url);
                break;
                
                case "post":
                default:
                $result = $request->post($url, $data);
                break;
            }

        } catch (RequestException $e) {
            report($e->getMessage());
            $result = $this->invalidResponse();
        }

        return $result;
    }

    final public function api(): static
    {
        $request = Http::accept($this->getAccept());

        if($this->hasToken()) {
            $request->withToken($this->getToken());
        }
        
        $request->withHeaders($this->getHeader());
        // ->retry(3, 1000, throw: false);

        static::$response = $this->dataProcess($request, $this->getMethod(), $this->getUrl(), $this->getData());
        return $this;
    }

    final public function send(Requestionable $request, ?Closure $process = null): static
    {
        if($process instanceof Closure) {
            $process($request);
        }

        return $this;
    }

    final public function response(): Response
    {
        return static::$response;
    }

    final public function result(): Collection
    {
        $response = $this->response();
        
        if(! $response->ok()) return collect([]);
        return $response->collect();
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