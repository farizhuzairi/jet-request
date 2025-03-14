<?php

namespace Jet\Request\Client\Traits;

use Closure;

trait UseKey
{
    protected static bool $withFormatting = false;
    protected static ?Closure $formater = null;
    protected ?string $token;

    public function __construct(
        ?string $token
    )
    {
        $this->reset($token);
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

    public function reset(?string $token = null): void
    {
        $this->token = ! empty($token) ? $token : null;
        static::$withFormatting = false;
        static::$formater = null;
    }
}