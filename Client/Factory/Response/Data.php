<?php

namespace Jet\Request\Client\Factory\Response;

use Illuminate\Http\Client\Response;
use Jet\Request\Client\Contracts\DataResponse;

class Data implements DataResponse
{
    protected Response $response;
    
    public function __construct(
        // public bool $successful,
        // public int $statusCode,
        // public ?string $message,
        // public array $results = [],
        // public array $meta = [],
        public $data,
        public $meta
    )
    {
        //
    }

    public static function response(Response $response): static
    {
        $self = new self(...$response->collect());
        return $self;
    }
}