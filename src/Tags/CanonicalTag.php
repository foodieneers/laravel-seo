<?php

namespace Foodieneers\Laravel\SEO\Tags;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Foodieneers\Laravel\SEO\Support\LinkTag;
use Foodieneers\Laravel\SEO\Support\RenderableCollection;
use Foodieneers\Laravel\SEO\Support\SEOData;

class CanonicalTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(?SEOData $SEOData = null): static
    {
        $collection = new static;

        if (config('seo.canonical_link')) {
            $collection->push(new LinkTag('canonical', $SEOData->canonical_url ?? $SEOData->url));
        }

        return $collection;
    }
}
