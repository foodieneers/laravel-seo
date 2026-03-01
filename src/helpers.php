<?php

use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\Support\SEOInputData;
use Foodieneers\Laravel\SEO\TagManager;

if (! function_exists('seo')) {
    function seo(SEOData|SEOInputData|null $source = null): TagManager
    {
        $tagManager = resolve(TagManager::class);

        if ($source instanceof SEOData || $source instanceof SEOInputData) {
            $tagManager->for($source);
        }

        return $tagManager;
    }
}
