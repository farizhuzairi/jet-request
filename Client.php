<?php

namespace Jet\Request;

use Closure;
use Jet\Request\RequestService;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Jet\Request\Client\Contracts\Requestionable;

class Client extends RequestService implements Requestionable
{
    /**
     * Client Request Construction
     * ---
     */
    public function __construct(
        array|string $data,
        ?string $method,
        ?string $accept
    )
    {
        parent::__construct(data: $data, method: $method, accept: $accept);
    }

    /**
     * Create New Request Static
     * 
     */
    public static function request(
        array|string $data = [],
        Closure|string|null $method = null,
        Closure|string|null $accept = null,
        ?Closure $closure = null
    ): Response|Collection|array
    {
        if($method instanceof Closure) {
            $closure = $method;
            $method = null;
        }

        if($accept instanceof Closure) {
            $closure = $accept;
            $accept = null;
        }
        
        $object = new self($data, $method, $accept);
        if(! empty($closure)) $closure($object);
        $object->api();

        return $object->send();
    }
}