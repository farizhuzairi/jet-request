<?php

namespace Jet\Request\Client\Http\Factory;

use Jet\Request\Client\Contracts\Keyable;

class Token implements Keyable
{
    public function getToken(): string
    {
        return config('jet-request.token');
    }
}