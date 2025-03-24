<?php

namespace Jet\Request\Client\Contracts;

abstract class DataResponse extends \Spatie\LaravelData\Data
{
    public function toArray(array $context = []): array
    {
        $arr = parent::toArray($context);

        if(isset($arr['data'])) {

            $data = collect($arr)->filter(function(mixed $value, string|int $key) {
                return $key == 'data';
            })->all();

            return array_merge($arr, $data['data']);

        }

        return $arr;
    }

    public function getDataResponse(array|string|null $key = null): mixed
    {
        if(empty($key)) {
            return $this->toArray();
        }

        if(is_array($key)) {
            return collect($this->toArray())->only($key)->all();
        }

        if(is_string($key) && array_key_exists($key, $this->toArray())) {
            return $this->toArray()[$key];
        }

        return null;
    }
}