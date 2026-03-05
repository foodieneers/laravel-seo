<?php

namespace Foodieneers\Laravel\SEO;

use Foodieneers\Laravel\SEO\Support\SchemaTagCollection;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\Tags\AlternateTags;
use Foodieneers\Laravel\SEO\Tags\AuthorTag;
use Foodieneers\Laravel\SEO\Tags\CanonicalTag;
use Foodieneers\Laravel\SEO\Tags\DescriptionTag;
use Foodieneers\Laravel\SEO\Tags\FaviconTag;
use Foodieneers\Laravel\SEO\Tags\ImageTag;
use Foodieneers\Laravel\SEO\Tags\OpenGraphTags;
use Foodieneers\Laravel\SEO\Tags\RobotsTag;
use Foodieneers\Laravel\SEO\Tags\SitemapTag;
use Foodieneers\Laravel\SEO\Tags\TitleTag;
use Foodieneers\Laravel\SEO\Tags\TwitterCardTags;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

/** @phpstan-consistent-constructor */
class TagCollection extends Collection
{
    public static function initialize(?SEOData $SEOData = null): static
    {
        $collection = new static;

        $tags = collect([
            RobotsTag::initialize($SEOData),
            CanonicalTag::initialize($SEOData),
            SitemapTag::initialize($SEOData),
            DescriptionTag::initialize($SEOData),
            AuthorTag::initialize($SEOData),
            TitleTag::initialize($SEOData),
            ImageTag::initialize($SEOData),
            FaviconTag::initialize($SEOData),
            OpenGraphTags::initialize($SEOData),
            TwitterCardTags::initialize($SEOData),
            AlternateTags::initialize($SEOData),
            SchemaTagCollection::initialize($SEOData),
        ])->reject(fn (?Renderable $item): bool => $item === null);

        foreach ($tags as $tag) {
            $collection->push($tag);
        }

        return $collection;
    }
}
