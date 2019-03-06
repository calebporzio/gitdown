<?php

namespace GitDown;

use Illuminate\Support\Facades\Facade as Facade;

class GitDown extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'gitdown';
    }
}
