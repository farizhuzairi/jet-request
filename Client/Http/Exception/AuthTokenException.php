<?php

namespace Jet\Request\Client\Http\Exception;

use Exception;
use Jet\Request\Client\Exception\UseException;
use Jet\Request\Client\Exception\Exceptionable;

class AuthTokenException extends Exception implements Exceptionable
{
    use UseException;

    protected $logLevelDefault = "error";
    protected $logMessageDefault = "Auth Token Exception.";
}