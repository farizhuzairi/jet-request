<?php

namespace Jet\Request\Client\Contracts;

abstract class DataResponse extends \Spatie\LaravelData\Data
{
    public function toArray(array $context = []): array
    {
        $arr = parent::toArray($context);

        if(isset($arr['data'])) {

            return collect($arr)->filter(function(mixed $value, string|int $key) {
                return $key !== 'data';
            })->all();

        }

        return $arr;
    }

    // public function setDataResponse(array|string|null $props = ["data", "links", "meta"]): void
    // {
    //     if(is_string($props) && property_exists($this, $props)) {
    //         $this->set_data_to_results($props, $this->{$props});
    //     }

    //     if(is_array($props)) {
    //         foreach ($props as $_data) {
    //             if(property_exists($this, $_data)) {
    //                 $this->set_data_to_results($_data, $this->{$_data});
    //             }
    //         }
    //     }
    // }

    // protected function set_data_to_results(string|int $key, array $data, string $results = "results"): void
    // {
    //     if(is_array($data) && isset($data['results'])) {
    //         $this->{$results}[$key] = $data['results'];
    //     }
    // }

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