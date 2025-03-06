<?php

namespace Jet\Request\Client;

use Jet\Request\Client\Keys;
use Illuminate\Support\ServiceProvider;
use Jet\Request\Client\Contracts\ApiKey;
use Illuminate\Contracts\Foundation\Application;

class JetRequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/jet-request.php', 'jet-request'
        );

        $this->defaultApi();
    }
    
    public function boot(): void
    {
        //
    }

    protected function defaultApi(): void
    {
        $this->app->scoped(ApiKey::class, function(Application $app) {
            $config = $app->config['jet-request'];
            $service = $config['token_service'];
            return new $service($config['token']);
        });
    }
}
