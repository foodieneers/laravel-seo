<?php

use Foodieneers\Laravel\SEO\SEOService;

if (! function_exists('seo')) {
    function seo(): string
    {
        return resolve(SEOService::class)->render();
    }
}
