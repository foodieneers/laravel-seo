<?php

namespace Foodieneers\Laravel\SEO\Tags;

use Foodieneers\Laravel\SEO\Support\LinkTag;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Collection;

class FaviconTag extends LinkTag
{
    public static function initialize(?SEOData $SEOData): ?static
    {
        $favicon = $SEOData?->favicon;

        if (! $favicon) {
            return null;
        }

        return new static(
            rel: 'shortcut icon',
            href: $favicon,
        );
    }

    public function collectAttributes(): Collection
    {
        return parent::collectAttributes()
            ->sortKeys();
    }
}
