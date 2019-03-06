<?php

namespace CalebPorzio;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GitDownServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('gitdown', function () {
            return '<style>'. GitDown::styles() .'</style>';
        });
    }
}
