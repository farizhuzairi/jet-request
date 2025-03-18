<?php

namespace Jet\Request\Client\Factory\Response;

use Illuminate\Http\Client\Response;
use Jet\Request\Client\Contracts\DataResponse;
use Jet\Request\Client\Contracts\Requestionable;
use Jet\Request\Client\Supports\InvalidResponse;
use Jet\Request\Client\Http\Exception\JetRequestException;

final class ResponseFactory
{
    protected ?string $dataClass = null;
    protected array $dataWrapper = [];

    public function __construct(
        protected Requestionable $request,
        protected ?Response $response,
        array $dataWrapper
    )
    {
        if(! empty($dataWrapper)) {
            $this->dataClass['object'];
            $this->dataWrapper['contents'];
        }
    }

    public static function response(Requestionable $request, ?Response $response): DataResponse
    {
        if($request->getDataWrapperName() === null) {}

        $self = new self($request, $response, $request->getDataWrapper());
        return $self->set_data_object();
    }

    private function set_data_object(): DataResponse
    {
        if(! class_exists($this->dataClass) || $this->response?->collect()?->isEmpty()) {
            report(new JetRequestException(
                "{$this->dataClass} not found, or invalid data response.",
                [
                    'request' => $this->request?->getUrl(),
                    'method' => $this->request?->getMethod(),
                    'error_details' => [
                        'data_wrapper' => $this->dataWrapper
                    ]
                ]
            ));
        }
        
        return $this->dataClass::from(...($this->response?->collect()?->only($this->dataWrapper) ?? []));
    }
}