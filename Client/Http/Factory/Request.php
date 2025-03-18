<?php

namespace Jet\Request\Client\Http\Factory;

use Closure;
use Jet\Request\Client\Http\Factory\RequestService;

class Request
{
    public function request(
        Closure|array $data = [],
        Closure|string|null $method = null,
        Closure|string|null $accept = null,
        ?Closure $request = null
    ): RequestService
    {
        if($data instanceof Closure) {
            $request = $data;
            $data = [];
        }

        if($method instanceof Closure) {
            $request = $method;
            $method = null;
        }

        if($accept instanceof Closure) {
            $request = $accept;
            $accept = null;
        }
        
        $_requestion = new RequestService($data, $method, $accept);
        return $_requestion->api($request);
    }
}