<?php

namespace Jet\Request\Client\Http\Exception;

use Exception;
use Jet\Request\Client\Exception\UseException;
use Jet\Request\Client\Exception\Exceptionable;

class JetRequestException extends Exception implements Exceptionable
{
    use UseException;

    protected $logLevelDefault = "error";
    protected $logMessageDefault = "Jet Request Exception.";
}