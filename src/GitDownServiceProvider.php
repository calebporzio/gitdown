<?php

namespace GitDown;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GitDownServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton('gitdown', function () {
            return new GitDown(
                config('gitdown.token'),
                config('gitdown.context'),
                config('gitdown.allowIframes')
            );
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
