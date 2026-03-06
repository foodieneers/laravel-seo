<?php

use Foodieneers\Laravel\SEO\Schema\Organization;

it('builds Organization schema payload without area served', function (): void {
    $schema = Organization::make(
        url: 'https://example.com',
        name: 'Example Org',
        author: 'Marco Azzari',
    )->toArray();

    expect($schema)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => 'https://example.com/#organization',
        'url' => 'https://example.com',
        'name' => 'Example Org',
        'logo' => 'http://localhost/logo.webp',
        'founder' => [
            '@type' => 'Person',
            '@id' => 'https://www.marcoazzari.com/#person',
            'name' => 'Marco Azzari',
        ],
    ]);
});

it('builds Organization schema payload with area served', function (): void {
    $schema = Organization::make(
        url: 'https://example.com',
        name: 'Example Org',
        author: 'Marco Azzari',
        area: 'Zurich',
    )->toArray();

    expect($schema)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => 'https://example.com/#organization',
        'areaServed' => [
            '@type' => 'City',
            'name' => 'Zurich',
        ],
    ]);
});
