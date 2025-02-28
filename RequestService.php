<?php

namespace Jet\Request;

use Illuminate\Http\Client\Response;

abstract class RequestService
{
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
    {}

    public function method(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function data(array|string $data): static
    {
        $this->data = $data;
        return $this;
    }

    protected function accept(string $accept): static
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
        if(! $this->httpRequest instanceof Response) {
            throw new \Exception("Error Processing Request: Invalid data object request.");
        }

        return $this->httpRequest;
    }
}