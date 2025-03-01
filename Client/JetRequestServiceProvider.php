<?php

namespace Jet\Request\Client;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Contracts\Foundation\Application;

class JetRequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/jet-request.php', 'jet-request'
        );
    }
    
    public function boot(): void
    {
        //
    }
}
