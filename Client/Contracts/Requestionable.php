<?php

namespace Jet\Request\Client\Contracts;

interface Requestionable
{
    /**
     * Create New Request Static
     * 
     */
    public static function request(
        \Closure|array $data = [],
        \Closure|string|null $method = null,
        \Closure|string|null $accept = null,
        \Closure|null $closure = null
    ): Requestionable;
}