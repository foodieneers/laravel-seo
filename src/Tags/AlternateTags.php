<?php

namespace Foodieneers\Laravel\SEO\Tags;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Foodieneers\Laravel\SEO\Support\RenderableCollection;
use Foodieneers\Laravel\SEO\Support\SEOData;

class AlternateTags extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData): ?static
    {
        if (! $SEOData->alternates) {
            return null;
        }

        return new static($SEOData->alternates);
    }
}
