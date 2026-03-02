<?php

namespace Foodieneers\Laravel\SEO\Facades;

use Illuminate\Support\Facades\Facade;

class SEOManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Foodieneers\Laravel\SEO\SEOManager::class;
    }
}
