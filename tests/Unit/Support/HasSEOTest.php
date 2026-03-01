<?php

use Foodieneers\Laravel\SEO\Tests\Fixtures\Page;

it('automatically associates a SEO model on creation', function (): void {
    $page = Page::create();

    expect($page->seo)
        ->not->toBeNull();
});
