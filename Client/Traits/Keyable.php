<?php

namespace Jet\Request\Client\Traits;

use Closure;
use Illuminate\Support\Facades\Crypt;
use Jet\Request\Client\Contracts\Keyable as KeyService;

trait Keyable
{
    protected ?KeyService $keyService = null;
    protected bool $useToken = false;

    public function keyService(KeyService $keyService, Closure $keyable): void
    {
        if($keyService instanceof KeyService) {

            $this->keyService = $keyService;

            if($keyable instanceof Closure) {
                $keyable($this);
            }

        }
    }

    public function hasToken(): bool
    {
        return $this->useToken;
    }

    public function token(?string $token = null): static
    {
        if(! empty($token)) {
            $this->setToken($token);
        }

        return $this;
    }

    public function setToken(string $token): void
    {
        // set to key service factory
        // set UseToken object as true if token not null
    }

    public function getToken(): ?string
    {
        // get from key service factory

        return null;
    }
}