<?php

namespace CalebPorzio;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GitDownServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app()->singleton('gitdown', function () {
            return new GitDown(config('gitdown.token'));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config-stub.php' => config_path('gitdown.php'),
        ]);

        Blade::directive('gitdown', function () {
            return '<style>'. $this->app->make('gitdown')->styles() .'</style>';
        });
    }
}
