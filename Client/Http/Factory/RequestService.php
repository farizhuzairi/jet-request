<?php

namespace Jet\Request\Client\Http\Factory;

use Closure;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Jet\Request\Client\Supports\Hostable;
use Illuminate\Http\Client\PendingRequest;
use Jet\Request\Client\Contracts\DataResponse;
use Jet\Request\Client\Contracts\Requestionable;
use Jet\Request\Client\Supports\InvalidResponse;
use Jet\Request\Client\Http\Factory\Response\ResponseFactory;

class RequestService implements Requestionable
{
    use Hostable;

    protected array $data = [];
    protected string $method;
    protected string $accept;

    private PendingRequest|Response $response;
    private array $dataContents = [];

    public const _METHOD = "post";
    public const _ACCEPT = "application/json";

    private DataResponse $dataResponse;
    
    public function __construct(
        array $data = [],
        ?string $method = null,
        ?string $accept = null
    )
    {
        $this->has_properties($data, $method, $accept);
        $this->has_default_headers();
        $this->has_default_hostable();

        if(empty($this->method)) $this->method = static::_METHOD;
        if(empty($this->accept)) $this->accept = static::_ACCEPT;
    }

    private function has_properties(array $data, ?string $method, ?string $accept): void
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
        $method = strtolower($method);
        if(in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
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

        $response = Http::accept($this->getAccept())
        ->withHeaders($this->getHeader());

        switch($this->getMethod()) {
            
            case "get";
            case "delete";
            case "post":
            case "put":
            case "patch":
            $response = $response->{$this->getMethod()}($this->getUrl(), $this->getData());
            break;

            default:
            $invalidResponse = new InvalidResponse();
            $response = $invalidResponse($this, null);
            break;

        }

        $this->set_data_response($response);
        return $this;
    }

    private function set_data_response(Response $response): void
    {
        $this->dataResponse = ResponseFactory::response(
            config('jet-request'),
            $this,
            $response,
            function($f) {
                $this->response = $f->getResponse();
                $this->dataContents = $f->getDataResultContents();
            }
        );
    }

    public function response(): Response
    {
        return $this->response;
    }

    public function results(array|string|null $key = null): array
    {
        $results = $this->dataResponse->getDataResponse($this->dataContents);

        if(! empty($key)) {

            if(is_string($key)) {
                $key = [$key];
            }

            return collect($results)->only($key)->toArray();

        }

        return $results;
    }

    public function successful(): bool
    {
        return $this->dataResponse->getDataResponse('successful');
    }

    public function statusCode(): int
    {
        return $this->dataResponse->getDataResponse('statusCode');
    }

    public function message(): ?string
    {
        return $this->dataResponse->getDataResponse('message');
    }

    public function getOriginalResults(bool $isHttp = false): array
    {
        if(! $isHttp) {
            return $this->dataResponse->getDataResponse($this->dataContents);
        }

        if(! $this->response()) {
            return [];
        }

        return $this->response()?->collect()?->toArray() ?? [];
    }

    public function getResponse(): Response
    {
        return $this->response();
    }

    public function getResults(array|string|null $key = null): array
    {
        return $this->results($key);
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