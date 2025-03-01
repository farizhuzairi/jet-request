<?php

namespace Jet\Request\Client\Traits;

trait Hostable
{
    /**
     * Http
     * 
     */
    protected string $http = 'https://';

    /**
     * URL tanpa http
     */
    protected string $host;

    /**
     * Endpoint, URL Parameters
     * 
     */
    protected string $endpoint;

    /**
     * Headers
     * 
     */
    protected array $headers = [];

    /**
     * Set Host without http
     * 
     * @return static
     */
    public function host(?string $host = null): static
    {
        if(empty($host)) {
            $host = "haschanetwork.local";
        }

        $this->host = $host;
        return $this;
    }

    /**
     * Set Endpoint as URL Parameters
     * 
     * @return static
     */
    public function endpoint(?string $endpoint = null): static
    {
        if(empty($endpoint)) {
            $endpoint = "/api";
        }

        if(empty($version)) {
            $version = "/1";
        }

        $topics = "";
        if(! empty($topic)) {
            if(is_array($topic)) {
                foreach($topic as $t) {
                    $topics .= $t;
                }
            }
            elseif(is_string($topic)) {
                $topics = $topic;
            }
        }

        $this->endpoint = "{$endpoint}{$version}{$topics}";
        return $this;
    }

    /**
     * Set Header Default
     * 
     */
    protected function setHeader(): static
    {
        $default = [
            'User-Agent' => 'EMS/1.0.0 Base/06846.347 ' . config('app.name'),
            'App-ID' => '',
            'Request-ID' => '',
            'Userable-Key' => '',
            'License-Key' => '',
            'Visit' => '',
        ];

        $this->headers = array_merge($this->headers, $default);
        return $this;
    }

    /**
     * Set Header
     * 
     */
    public function header(array|string $header, mixed $value = null): static
    {
        if(is_array($header)) {
            $this->headers = array_merge($this->headers, $header);
        }
        
        if(is_string($header) && ! empty($value)) {
            $this->headers = array_merge($this->headers, [$header => $value]);
        }

        return $this;
    }

    /**
     * Set Header
     * 
     */
    public function headers(array $header): static
    {
        return $this->header($header);
    }

    /**
     * Get Header
     * 
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get Header
     * 
     */
    public function getHeader(?string $key = null): array
    {
        $arr = $this->headers;
        $result = isset($arr[$key]) ? [$key => $arr[$key]] : null;

        if(empty($result)) {
            $result = $arr;
        }

        return $result;
    }
}