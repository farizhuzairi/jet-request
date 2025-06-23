<?php

namespace Jet\Request\Client\Http\Factory\Response;

use Jet\Request\Client\Contracts\DataResponse;
use Jet\Request\Client\Http\Factory\Response\DataResults;

class DataMeta extends DataResponse
{
    public function __construct(
        public DataResults $data,
        public ?array $author = [],
        public array $meta = [],
    )
    {}
}