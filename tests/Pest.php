<?php

use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\TagManager;
use Foodieneers\Laravel\SEO\Tests\TestCase;

uses(TestCase::class)
    ->in(__DIR__);

function renderSeo(SEOData $input): string
{
    return resolve(TagManager::class)
        ->for($input)
        ->render();
}
