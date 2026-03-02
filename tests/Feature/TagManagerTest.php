<?php

use Foodieneers\Laravel\SEO\Support\SEOInputData;
use Foodieneers\Laravel\SEO\TagManager;
it('builds and renders tags from SEOInputData', function (): void {
    $output = resolve(TagManager::class)
        ->for(new SEOInputData(
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

    $manager = resolve(TagManager::class)->for(new SEOInputData(
        url: 'https://example.com/posts/my-first-post',
    ));

    expect($manager->SEOData?->title)->toBe('My First Post');
});

it('uses homepage title for root URL when configured', function (): void {
    config()->set('seo.title.homepage_title', 'Custom homepage title');

    $manager = resolve(TagManager::class)->for(new SEOInputData(
        url: url('/'),
    ));

    expect($manager->SEOData?->title)->toBe('Custom homepage title');
});

it('marks robots as noindex when requested', function (): void {
    $manager = resolve(TagManager::class)->for(new SEOInputData(
        markAsNoindex: true,
        url: 'https://example.com/private',
    ));

    expect($manager->SEOData?->robots)->toBe('noindex, nofollow');
});
