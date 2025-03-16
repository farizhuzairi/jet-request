<?php

namespace Jet\Request\Client\Traits;

use Closure;
use Illuminate\Support\Facades\Crypt;
use Jet\Request\Client\Contracts\ApiKey;
use Jet\Request\Client\Exception\InvalidAuthToken;

trait Keyable
{
    protected ?string $token = null;
    protected ?string $appId = null;

    public function withToken(?string $token = null): void
    {
        if(! empty($token)) {
            $this->setToken($token);
        }
    }

    public function setKeyable(ApiKey $apiKey, Closure $keyable): void
    {
        $token = $keyable($apiKey);

        if(empty($token) || ! is_string($token)) {
            $token = null;
            report(new InvalidAuthToken);
        }

        if(! empty($token)) {
            $this->setToken($token);
        }
    }

    public function token(?string $token = null): string
    {
        if(! empty($token)) {
            $this->setToken($token);
        }

        return $this->getToken();
    }

    public function setToken(string $token): void
    {
        if(! empty($token)) {
            $token = "{$token}";
            $this->token = Crypt::encrypt($token);
        }
    }

    public function getToken(): ?string
    {
        if($this->hasToken()) {
            return Crypt::decrypt($this->token);
        }

        return null;
    }

    public function hasToken(): bool
    {
        if(! empty($this->token)) {
            return true;
        }

        return false;
    }

    public function appId(?string $appId): ?string
    {
        if(! empty($appId)) {
            $this->setAppId($appId);
        }

        return $this->getAppId();
    }

    public function setAppId(?string $appId): void
    {
        $this->appId = (string) $appId;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }
}