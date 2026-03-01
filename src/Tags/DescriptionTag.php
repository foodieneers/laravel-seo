<?php

namespace Foodieneers\Laravel\SEO\Tags;

use Foodieneers\Laravel\SEO\Support\MetaTag;
use Foodieneers\Laravel\SEO\Support\SEOData;

class DescriptionTag extends MetaTag
{
    public static function initialize(?SEOData $SEOData): ?MetaTag
    {
        $description = $SEOData?->description;

        if (! $description) {
            return null;
        }

        return new MetaTag(
            name: 'description',
            content: $description
        );
    }
}
