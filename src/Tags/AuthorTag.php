<?php

namespace Foodieneers\Laravel\SEO\Tags;

use Foodieneers\Laravel\SEO\Support\MetaTag;
use Foodieneers\Laravel\SEO\Support\SEOData;

class AuthorTag extends MetaTag
{
    public static function initialize(?SEOData $SEOData): ?MetaTag
    {
        $author = $SEOData?->author;

        if (! $author) {
            return null;
        }

        return new MetaTag(
            name: 'author',
            content: $author
        );
    }
}
