<?php

namespace GitDown;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GitDownServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton('gitdown', function () {
            $allowedTags = config('gitdown.allowedTags', []);

            // Support the legacy config option: allowIframes
            if (config('gitdown.allowIframes') && array_search('iframe', $allowedTags) === false) {
                $allowedTags[] = 'iframe';
            }

            return new GitDown(
                config('gitdown.token'),
                config('gitdown.context'),
                $allowedTags,
                config('gitdown.syntaxTheme')
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
