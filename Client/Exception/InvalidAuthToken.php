<?php

namespace Jet\Request\Client\Exception;

use Exception;
use Illuminate\Support\Facades\Log;

class InvalidAuthToken extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        protected array $error = [],
    )
    {
        $msg = "Invalid Auth Token.";
        $message = !empty($message) ? "{$msg} {$message}" : $msg;
        parent::__construct($message, $code);
    }

    public function report(): void
    {
        Log::warning($this->message, $this->error);
    }
}