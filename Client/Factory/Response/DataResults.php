<?php

namespace Jet\Request\Client\Factory\Response;

class DataResults extends \Jet\Request\Client\Contracts\DataResponse
{
    public function __construct(
        public bool $successful = false,
        public int $statusCode = 500,
        public ?string $message = "Server Error. There was a problem with the internal server.",
        public array $results = [],
    )
    {}
}