<?php

namespace Jet\Request\Client\Exception;

use Illuminate\Support\Facades\Log;

trait UseException
{
    protected array $error = [];

    public function __construct(
        string $message = '',
        array|int $code = 0,
        array $error = [],
    )
    {
        if(is_array($code)) {
            $error = $code;
            $code = 0;
        }

        $this->error = $error;

        $msg = $this->use_log_message();
        $message = !empty($message) ? "{$msg} {$message}" : $msg;
        parent::__construct($message, $code);
    }

    public function report(): void
    {
        $level = $this->use_log_level();

        Log::{$level}($this->message, array_merge(
            $this->error,
            [
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                // 'trace' => $this->getTraceAsString()
            ]
        ));
    }

    protected function use_log_level(): string
    {
        if(property_exists($this, 'logLevelDefault')) {
            return $this->logLevelDefault;
        }

        return 'error';
    }

    protected function use_log_message(): string
    {
        if(property_exists($this, 'logMessageDefault')) {
            return $this->logMessageDefault;
        }
        

        return 'Error Processing Request';
    }
}