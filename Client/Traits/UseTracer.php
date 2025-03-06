<?php

namespace Jet\Request\Client\Traits;

trait UseTracer
{
    protected ?string $requestId = null;

    public function requestId(?string $id = null): static
    {
        if(! empty($id)) {
            $this->setRequestId($id);
        }

        return $this;
    }

    public function setRequestId(string $id): void
    {
        if(! empty($id)) {
            $this->requestId = $id;
        }
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }
}