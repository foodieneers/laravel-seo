<?php

use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\TagManager;

if (! function_exists('seo')) {
    function seo(SEOData | null $source = null): TagManager
    {
        $tagManager = app(TagManager::class);

        if ($source) {
            $tagManager->for($source);
        }

        return $tagManager;
    }
}
