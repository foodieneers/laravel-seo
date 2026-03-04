<?php

use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\TagManager;

it('builds and renders tags from SEOData', function (): void {
    $output = resolve(TagManager::class)
        ->for(new SEOData(
            title: 'Awesome News - My Project',
            description: 'Custom description',
            url: 'https://example.com/news',
        ))
        ->render();

    expect($output)
        ->toContain('<title>Awesome News - My Project</title>')
        ->toContain('<meta name="description" content="Custom description">');
});

it('infers title from URL when enabled', function (): void {
    config()->set('seo.title.infer_title_from_url', true);

    $manager = resolve(TagManager::class)->for(new SEOData(
        url: 'https://example.com/posts/my-first-post',
    ));

    expect($manager->SEOData?->title)->toBe('My First Post');
});

it('marks robots as noindex when requested', function (): void {
    $manager = resolve(TagManager::class)->for(new SEOData(
        url: 'https://example.com/private',
        markAsNoindex: true,
    ));

    expect($manager->SEOData?->robots)->toBe('noindex, nofollow');
});
