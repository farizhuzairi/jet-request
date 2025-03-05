<?php

namespace Jet\Request\Client\Traits;

use Illuminate\Support\Facades\Crypt;

trait Keyable
{
    protected ?string $token;
    protected ?string $appId = null;

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
            $token = "Bearer {$token}";
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