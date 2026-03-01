<?php

namespace Foodieneers\Laravel\SEO\Tags;

use Foodieneers\Laravel\SEO\Support\MetaTag;
use Foodieneers\Laravel\SEO\Support\RenderableCollection;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class RobotsTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(?SEOData $SEOData = null): static
    {
        $collection = new static;

        $robotsContent = config('seo.robots.default');

        if (! config('seo.robots.force_default')) {
            $robotsContent = $SEOData?->robots ?? $robotsContent;
        }

        $collection->push(new MetaTag('robots', $robotsContent));

        return $collection;
    }
}
