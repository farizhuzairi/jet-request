<?php

namespace Jet\Request\Client\Factory\Response;

use Closure;
use Illuminate\Http\Client\Response;
use Jet\Request\Client\Contracts\DataResponse;
use Jet\Request\Client\Contracts\Requestionable;
use Jet\Request\Client\Supports\InvalidResponse;
use Jet\Request\Client\Factory\Response\DataResults;
use Jet\Request\Client\Http\Exception\JetRequestException;

final class ResponseFactory
{
    protected ?string $dataClass = null;
    protected array $dataWrapper = [];

    private static array $_WRAPPERS;
    private static ?string $_DATA_WRAPPER;

    public function __construct(
        protected Requestionable $request,
        protected ?Response $response,
    )
    {
        $config = config('jet-request');
        static::$_DATA_WRAPPER = $config['data_wrapper'];
        static::$_WRAPPERS = $config['wrappers'];

        if(! empty($this->getDataWrapper())) {
            $this->dataClass = $this->getDataWrapper()['class'];
            $this->dataWrapper = $this->getDataWrapper()['contents'];
        }
    }

    public function getDataWrapperName(): ?string
    {
        return static::$_DATA_WRAPPER;
    }

    public function getDataWrapper(): array
    {
        return static::$_WRAPPERS[static::$_DATA_WRAPPER] ?? [];
    }

    public function getDataClass(): string
    {
        return $this->dataClass ?? "";
    }

    public function getRequest(): Requestionable
    {
        return $this->request;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function getDataResultContents(): array
    {
        return ['results', 'links', 'meta', 'successful', 'statusCode', 'message'];
    }

    public static function response(Requestionable $request, ?Response $response, Closure $callback): DataResponse
    {
        $self = new self($request, $response);
        return $self->set_data_object($callback);
    }

    private function set_data_object(Closure $callback): DataResponse
    {
        $dataResults = null;

        if($this->has_not_serialized()) {
            $invalidResponse = new InvalidResponse();
            $this->response = $invalidResponse($this->getRequest(), $this->getResponse());
        };

        try {

            $__respone_data_collect = $this->getResponse()->collect();

            if($__respone_data_collect->has($this->getDataWrapper())) {
                $dataResults = $this->dataClass::from($__respone_data_collect->toArray());
            }
            else {
                foreach (static::$_WRAPPERS as $dataWrap) {
                    if($__respone_data_collect->hasAny($dataWrap['contents'])) {
                        $dataClass = $dataWrap['class'];
                        $dataResults = $dataClass::from($__respone_data_collect->toArray());
                        break;
                    }
                }
            }
            
            if(empty($dataResults)) {
                throw new JetRequestException("Unable to capture response object data.");
            }

        } catch (JetRequestException $e) {

            report($e);

            // reset to default
            $invalidResponse = new InvalidResponse();
            $this->response = $invalidResponse($this->getRequest(), $this->getResponse());
            $dataResults = $this->_default_data_response($this->response->collect()->toArray());

        } finally {
            $callback($this);
        }

        return $dataResults;
    }

    private function has_not_serialized(): bool
    {
        if(! class_exists($this->dataClass)) {
            report(new JetRequestException("{$this->dataClass} not found."));
            return true;
        }

        if(empty($this->response)) {
            report(new JetRequestException("Response Instance not available."));
            return true;
        }

        if($this->response->collect()->isEmpty()) {
            report(new JetRequestException("The response data does not meet the object standards for serialization.."));
            return true;
        }

        return false;
    }

    private function _default_data_response(array $data): DataResponse
    {
        return DataResults::from($data);
    }
}