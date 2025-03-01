<?php

namespace Jet\Request\Client\Contracts;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;

interface Requestionable
{
    /**
     * Create New Request Static
     * 
     */
    public static function request(
        array|string $data = [],
        Closure|string|null $method = null,
        Closure|string|null $accept = null,
        ?Closure $closure = null
    ): Response|Collection|array;
}