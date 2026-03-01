<?php

namespace Foodieneers\Laravel\SEO\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getSEODataTransformers()
 * @method static array getTagTransformers()
 * @method static \Foodieneers\Laravel\SEO\SEOManager SEODataTransformer( Closure $transformer )
 * @method static \Foodieneers\Laravel\SEO\SEOManager tagTransformer( Closure $transformer )
 */
class SEOManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Foodieneers\Laravel\SEO\SEOManager::class;
    }
}
