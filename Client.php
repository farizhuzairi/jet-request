<?php

namespace Jet\Request;

class Client extends \Illuminate\Support\Facades\Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'client-request-factory';
    }
}