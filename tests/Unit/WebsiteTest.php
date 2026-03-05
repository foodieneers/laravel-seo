<?php

use Foodieneers\Laravel\SEO\Schema\Website;

it('builds Website schema payload without author', function (): void {
    $schema = Website::make(
        url: 'https://example.com',
        name: 'Example',
    )->toArray();

    expect($schema)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => 'https://example.com/#website',
        'url' => 'https://example.com',
        'name' => 'Example',
    ]);
});

it('builds Website schema payload with author', function (): void {
    $schema = Website::make(
        url: 'https://example.com',
        name: 'Example',
        author: 'Marco Azzari',
    )->toArray();

    expect($schema)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => 'https://example.com/#website',
        'url' => 'https://example.com',
        'name' => 'Example',
        'author' => [
            '@type' => 'Person',
            '@id' => 'https://www.marcoazzari.com/#person',
            'name' => 'Marco Azzari',
        ],
    ]);
});
