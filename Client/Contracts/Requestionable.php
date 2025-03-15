<?php

namespace Jet\Request\Client\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;

interface Requestionable
{
    public static function request(
        \Closure|array $data = [],
        \Closure|string|null $method = null,
        \Closure|string|null $accept = null,
        \Closure|null $closure = null
    ): self;

    public function getResponse(): Response;
    public function getResult(): Collection;
}