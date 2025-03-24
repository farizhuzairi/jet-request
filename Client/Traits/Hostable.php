<?php

namespace Jet\Request\Client\Traits;

use Illuminate\Http\Client\RequestException;

trait Hostable
{
    use
    \Jet\Request\Client\Traits\Keyable;

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

    protected function setDefaultWithStaticVar(?string $value, string $staticVar): ?string
    {
        if(! empty($value) && property_exists($this, $staticVar)) {
            static::${$staticVar} = $value;
        }

        return $value;
    }

    protected function getStrWithPrefix(?string $str, string $prefix = "/"): ?string
    {
        if(! empty($str)) {
            return $prefix . $str;
        }

        return null;
    }

    protected function has_default_headers(): static
    {
        $default = [
            'User-Agent' => 'EMS/1.0.0 Base/06846.347 ' . config('app.name'),
            'App-ID' => null,
            'Request-ID' => null,
        ];

        $this->headers = array_merge($this->headers, $default);
        return $this;
    }

    /**
     * Set Hostable
     * Default attributes
     * 
     */
    protected function has_default_hostable(): void
    {
        $config = config('jet-request');

        if(is_array($config)) {
            
            try {

                $api = $config['api'];
                static::$http = $this->setDefaultWithStaticVar($api['http'], '_HTTP');
                static::$host = $this->setDefaultWithStaticVar($api['host'], '_HOST');
                static::$endpoint = $this->setDefaultWithStaticVar($api['endpoint'], '_ENDPOINT');
                static::$version = $this->setDefaultWithStaticVar($api['version'], '_VERSION');
                
                $this->useDefaultUrl();

            } catch (RequestException $e) {
                report("Jet Request Config invalid: {$e->getMessage()}");
            }

        }
    }

    protected function useDefaultUrl(string $url = ""): void
    {
        if(empty($this->url) || empty($url)) {

            $url .= $this->httpHost();
            $url .= $this->endpoint();
            $url .= $this->version();
            $url .= $this->getTopics();
            $this->url = $url;

        } else {

            $this->url = $url;

        }
    }

    public function httpHost(): string
    {
        return static::$http . static::$host;
    }

    public function endpoint(): string
    {
        return $this->getStrWithPrefix(static::$endpoint) ?? "";
    }

    public function version(): string
    {
        return $this->getStrWithPrefix(static::$version) ?? "";
    }

    public function topics(?string $topics = null): static
    {
        if(! empty($topics)) {
            $this->setTopics($topics);
        }

        return $this;
    }

    protected function setTopics(string $topics): void
    {
        if(! empty($topics)) {
            static::$topics = $topics;
        }
    }

    public function getTopics(): string
    {
        return $this->getStrWithPrefix(static::$topics) ?? "";
    }

    public function url(?string $url = "", bool $isNewUrl = false): static
    {
        if(! empty($url)) {
            $this->setUrl($url, $isNewUrl);
        }
        
        return $this;
    }

    protected function setUrl(string $url, bool $isNewUrl = false): void
    {
        if($isNewUrl) {
            $this->url = $url;
        }
        else {
            $this->url .= "/" .$url;
        }
    }

    public function getUrl(): string 
    {
        return $this->url;
    }

    public function header(array|string $header, mixed $value = null): static
    {
        if(is_array($header)) {
            $this->setHeader($header);
        }
        
        if(is_string($header) && ! empty($value)) {
            $this->setHeader($header, $value);
        }

        return $this;
    }

    protected function setHeader(array|string $header, mixed $value = null): void
    {
        if(is_array($header)) {
            $this->headers = array_merge($this->headers, $header);
        }
        
        if(is_string($header) && ! empty($value)) {
            $this->headers = array_merge($this->headers, [$header => $value]);
        }
    }

    public function getHeader(?string $key = null): array
    {
        $arr = $this->headers;
        $result = isset($arr[$key]) ? [$key => $arr[$key]] : null;

        if(empty($result)) {
            $result = $arr;
        }

        return (array) $result;
    }

    public function headers(array $header): static
    {
        $this->setHeader($header);
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->getHeader();
    }
}