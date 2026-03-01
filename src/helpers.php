<?php

use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\TagManager;

if (! function_exists('seo')) {
    function seo(?SEOData $source = null): TagManager
    {
        $tagManager = resolve(TagManager::class);

        if ($source instanceof SEOData) {
            $tagManager->for($source);
        }

        return $tagManager;
    }
}
