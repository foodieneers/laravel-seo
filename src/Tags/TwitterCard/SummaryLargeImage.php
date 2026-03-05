<?php

namespace Foodieneers\Laravel\SEO\Tags\TwitterCard;

use Foodieneers\Laravel\SEO\Support\ImageMeta;
use Foodieneers\Laravel\SEO\Support\RenderableCollection;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\Support\TwitterCardTag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

/** @phpstan-consistent-constructor */
class SummaryLargeImage extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData): static
    {
        $collection = new static;

        if ($SEOData->imageMeta instanceof ImageMeta) {
            if ($SEOData->imageMeta->width < 300) {
                return $collection;
            }

            if ($SEOData->imageMeta->height < 157) {
                return $collection;
            }

            if ($SEOData->imageMeta->width > 4096) {
                return $collection;
            }

            if ($SEOData->imageMeta->height > 4096) {
                return $collection;
            }
        }

        $collection->push(new TwitterCardTag('card', 'summary_large_image'));

        if ($SEOData->image) {
            $collection->push(new TwitterCardTag('image', new HtmlString($SEOData->image)));

            if ($SEOData->imageMeta instanceof ImageMeta) {
                $collection->push(new TwitterCardTag('image:width', (string) $SEOData->imageMeta->width));
                $collection->push(new TwitterCardTag('image:height', (string) $SEOData->imageMeta->height));
            }
        }

        return $collection;
    }
}
