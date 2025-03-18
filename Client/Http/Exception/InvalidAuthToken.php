<?php

namespace Jet\Request\Client\Http\Exception;

use Exception;
use Illuminate\Support\Facades\Log;

class InvalidAuthToken extends Exception
{
    public function __construct(
        string $message = '',
        array|int $code = 0,
        protected array $error = [],
    )
    {
        if(is_array($code)) {
            $error = $code;
            $code = 0;
        }

        $msg = "Invalid Auth Token.";
        $message = !empty($message) ? "{$msg} {$message}" : $msg;
        parent::__construct($message, $code);
    }

    public function report(): void
    {
        Log::warning($this->message, array_merge(
            $this->error,
            [
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'trace' => $this->getTraceAsString()
            ]
        ));
    }
}