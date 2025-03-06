<?php

namespace Jet\Request\Client;

use Closure;
use Jet\Request\Client\Traits\UseKey;
use Jet\Request\Client\Contracts\ApiKey;

class Keys implements ApiKey
{
    use UseKey;

    public function token(): ?string
    {
        return $this->getWithFormat($this->token);
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getToken(): ?string
    {
        return $this->token();
    }

    private function getWithFormat(?string $default): ?string
    {
        $token = $default;

        if(static::$withFormatting) {
            $formater = static::$formater;
            $token = $formater($this);
        }

        return $token;
    }

    public function formatted(?string $token, ?Closure $formater): static
    {
        if(! empty($token) && $formater instanceof Closure) {
            static::$withFormatting = true;
            static::$formater = $formater;
        }

        return $this;
    }

    public function reset(?string $token = null): void
    {
        $this->token = ! empty($token) ? $token : null;
        static::$withFormatting = false;
        static::$formater = null;
    }
}