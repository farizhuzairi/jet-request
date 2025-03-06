<?php

namespace Jet\Request\Client\Contracts;

use Closure;

interface ApiKey
{
    public function setToken(?string $token): void;
    public function getToken(): ?string;
    public function formatted(?string $token, ?Closure $formater): static;
    public function reset(?string $token = null): void;
}