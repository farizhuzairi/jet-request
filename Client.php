<?php

namespace Jet\Request;

use Closure;
use Jet\Request\RequestService;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Jet\Request\Client\Contracts\Requestionable;

class Client extends RequestService implements Requestionable
{
    public function __construct(
        array $data,
        ?string $method,
        ?string $accept
    )
    {
        parent::__construct(data: $data, method: $method, accept: $accept);
    }

    public static function request(
        Closure|array $data = [],
        Closure|string|null $method = null,
        Closure|string|null $accept = null,
        ?Closure $request = null
    ): Requestionable
    {
        if($data instanceof Closure) {
            $request = $data;
            $data = [];
        }

        if($method instanceof Closure) {
            $request = $method;
            $method = null;
        }

        if($accept instanceof Closure) {
            $request = $accept;
            $accept = null;
        }
        
        $_requestion = new self($data, $method, $accept);
        return $_requestion->api($request);
    }

    public function getResponse(): Response
    {
        return $this->response();
    }

    public function getResult(): Collection
    {
        return $this->result();
    }
}