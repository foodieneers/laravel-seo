<?php

namespace Foodieneers\Laravel\SEO\Tags;

use Foodieneers\Laravel\SEO\Support\RenderableCollection;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\Support\SitemapTag as SitemapTagSupport;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class SitemapTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(?SEOData $SEOData = null): static
    {
        $collection = new static;

        if ($sitemap = config('seo.sitemap')) {
            $collection->push(new SitemapTagSupport($sitemap));
        }

        return $collection;
    }
}
