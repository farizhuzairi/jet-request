<?php

namespace Jet\Request\Client\Factory\Response;

class DataMeta extends \Jet\Request\Client\Factory\Response\DataResults
{
    public function __construct(
        public array $data = [],
        public array $links = [],
        public array $meta = [],
    )
    {
        if(is_array($data)) parent::__construct(...$data);
    }
}