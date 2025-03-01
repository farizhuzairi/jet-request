<?php

namespace Jet\Request\Client\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

trait Hostable
{
    /**
     * Http
     * 
     * @var string
     */
    protected static $http;

    /**
     * Main URL, domain
     * without http
     * 
     * @var string
     */
    protected static $host;

    /**
     * Endpoint, URL Parameters
     * 
     * @var string
     */
    protected static $endpoint;

    /**
     * Version, URL Parameters
     * 
     * @var string
     */
    protected static $version;

    /**
     * Topics, URL Parameters
     * 
     * @var string
     */
    protected static $topics;

    /**
     * Headers
     * 
     * @var array
     */
    protected $headers = [];

    /**
     * Default Host
     * 
     * @var string
     */
    protected static $_HTTP;

    /**
     * Default Host
     * 
     * @var string
     */
    protected static $_HOST;

    /**
     * Default Endpoint
     * 
     * @var string
     */
    protected static $_ENDPOINT;

    /**
     * Api URL created.
     * Or user created optionally, new URL Parameters
     * 
     * @var string
     */
    protected $url;

    /**
     * Default Endpoint
     * 
     * @var string
     */
    protected static $_VERSION;

    protected function getStrWithPrefix(?string $str, string $prefix = "/"): ?string
    {
        if(! empty($str)) {
            return $prefix . $str;
        }

        return null;
    }

    /**
     * Set Hostable
     * Default attributes
     * 
     */
    protected function defaultHostable(): void
    {
        $config = config('jet-request');

        if(is_array($config)) {
            
            try {
                $api = $config['api'];
                static::$_HTTP = $api['http'];
                static::$_HOST = $api['host'];
                static::$_ENDPOINT = $api['endpoint'];
                static::$_VERSION = $api['version'];
            } catch (RequestException $e) {
                Log::error("Jet Request Config invalid: {$e->getMessage()}");
            }

        }

        $this->setUrl();
    }

    public function http(?string $http): static
    {
        static::$http = ! empty($http) ? (string) $http : static::$_HTTP;
        return $this;
    }

    public function host(?string $host): static
    {
        static::$host = ! empty($host) ? (string) $host : static::$_HOST;
        return $this;
    }

    protected function getHttpHost(): string
    {
        $url = "";
        $url .= $this->getStrWithPrefix(static::$http);
        $url .= $this->getStrWithPrefix(static::$host);

        return $url;
    }

    public function endpoint(string $endpoint): static
    {
        static::$endpoint = $endpoint;
        return $this;
    }

    protected function getEndpoint(): string
    {
        return $this->getStrWithPrefix(static::$endpoint) ?? "";
    }

    public function version(string $version): static
    {
        static::$version = $version;
        return $this;
    }

    protected function getVersion(): string
    {
        return $this->getStrWithPrefix(static::$version) ?? "";
    }

    public function topics(string $topics): static
    {
        static::$topics = $topics;
        return $this;
    }

    protected function getTopics(): string
    {
        return $this->getStrWithPrefix(static::$topics) ?? "";
    }

    public function url(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    protected function setUrl(string $url = ""): void
    {
        if(empty($this->url) || empty($url)) {

            $url .= $this->getHttpHost();
            $url .= $this->getEndpoint();
            $url .= $this->getVersion();
            $url .= $this->getTopics();
            dd($url);
            $this->url = $url;

        } else {

            $this->url = $url;

        }
    }

    protected function getUrl(): string 
    {
        return $this->url;
    }

    protected function defaultHeader(): static
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
     * Set Header, add key and value,
     * or use array with header key
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
     * Set Header, add key and value as array
     */
    public function headers(array $header): static
    {
        return $this->header($header);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

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