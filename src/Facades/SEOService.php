<?php

namespace Foodieneers\Laravel\SEO\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setData(\Foodieneers\Laravel\SEO\Support\SEOInputData $data)
 * @method static bool hasData()
 * @method static void reset()
 * @method static string render()
 */
class SEOService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Foodieneers\Laravel\SEO\SEOService::class;
    }
}
