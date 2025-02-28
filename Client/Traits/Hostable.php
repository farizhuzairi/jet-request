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
}