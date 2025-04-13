<?php

namespace Jet\Request\Client;

use Illuminate\Support\ServiceProvider;
use Jet\Request\Client\Contracts\Keyable;
use Jet\Request\Client\Http\Factory\Request as ClientRequestFactory;
use Illuminate\Contracts\Foundation\Application;

class JetRequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/jet-request.php', 'jet-request'
        );

        $this->default_api_tokens();
        $this->register_bind();
    }
    
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/jet-request.php' => config_path('jet-request.php'),
        ], 'jet-request');
    }

    protected function default_api_tokens(): void
    {
        $this->app->scoped(Keyable::class, function(Application $app) {
            $service = $app->config['jet-request.token_service'];
            return new $service();
        });
    }

    protected function register_bind(): void
    {
        $this->app->bind('client-request-factory', function() {
            return new ClientRequestFactory();
        });
    }
}
