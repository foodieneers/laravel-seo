<?php

namespace Foodieneers\Laravel\SEO\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class SchemaTagCollection extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(?SEOData $SEOData = null): void
    {
      
    }
}
