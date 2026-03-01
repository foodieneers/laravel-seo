<?php

namespace Foodieneers\Laravel\SEO\Tags;

use Foodieneers\Laravel\SEO\Support\MetaTag;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Illuminate\Support\HtmlString;

class ImageTag extends MetaTag
{
    public static function initialize(?SEOData $SEOData): ?MetaTag
    {
        $image = $SEOData?->image;

        if (! $image) {
            return null;
        }

        return new MetaTag(
            name: 'image',
            content: new HtmlString($image),
        );
    }
}
