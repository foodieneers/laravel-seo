<?php

namespace Foodieneers\Laravel\SEO\Tags;

use Foodieneers\Laravel\SEO\Support\ImageMeta;
use Foodieneers\Laravel\SEO\Support\MetaContentTag;
use Foodieneers\Laravel\SEO\Support\OpenGraphTag;
use Foodieneers\Laravel\SEO\Support\RenderableCollection;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

/** @phpstan-consistent-constructor */
class OpenGraphTags extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData): static
    {
        $collection = new static;

        if ($SEOData->openGraphTitle) {
            $collection->push(new OpenGraphTag('title', $SEOData->openGraphTitle));
        } elseif ($SEOData->title) {
            $collection->push(new OpenGraphTag('title', $SEOData->title));
        }

        if ($SEOData->description) {
            $collection->push(new OpenGraphTag('description', $SEOData->description));
        }

        if ($SEOData->locale) {
            $collection->push(new OpenGraphTag('locale', $SEOData->locale));
        }

        if ($SEOData->image) {
            $collection->push(new OpenGraphTag('image', new HtmlString($SEOData->image)));

            if ($SEOData->imageMeta instanceof ImageMeta) {
                if ($SEOData->imageMeta->width !== null) {
                    $collection->push(new OpenGraphTag('image:width', (string) $SEOData->imageMeta->width));
                }

                if ($SEOData->imageMeta->height !== null) {
                    $collection->push(new OpenGraphTag('image:height', (string) $SEOData->imageMeta->height));
                }
            }
        }

        $collection->push(new OpenGraphTag('url', $SEOData->url));

        if ($SEOData->site_name) {
            $collection->push(new OpenGraphTag('site_name', $SEOData->site_name));
        }

        if ($SEOData->type) {
            $collection->push(new OpenGraphTag('type', $SEOData->type));
        }

        if ($SEOData->published_at && $SEOData->type === 'article') {
            $collection->push(new MetaContentTag('article:published_at', $SEOData->published_at->toIso8601String()));
        }

        if ($SEOData->modified_at && $SEOData->type === 'article') {
            $collection->push(new MetaContentTag('article:modified_at', $SEOData->modified_at->toIso8601String()));
        }

        if ($SEOData->section && $SEOData->type === 'article') {
            $collection->push(new MetaContentTag('article:section', $SEOData->section));
        }

        if ($SEOData->tags && $SEOData->type === 'article') {
            foreach ($SEOData->tags as $tag) {
                $collection->push(new MetaContentTag('article:tag', $tag));
            }
        }

        return $collection;
    }
}
