<?php

namespace Jet\Request;

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
        protected string $method,
        protected array|string $data,
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
}