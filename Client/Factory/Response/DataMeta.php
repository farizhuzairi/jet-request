<?php

namespace Jet\Request\Client\Factory\Response;

use Jet\Request\Client\Contracts\DataResponse;
use Jet\Request\Client\Factory\Response\DataResults;

class DataMeta extends DataResponse
{
    public function __construct(
        public DataResults $data,
        public ?array $author = [],
        public array $meta = [],
    )
    {
        // if(is_array($data)) parent::__construct(...$data);
    }
}