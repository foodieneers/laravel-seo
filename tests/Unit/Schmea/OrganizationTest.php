<?php

use Foodieneers\Laravel\SEO\Schema\Organization;

it('builds Organization schema with default area served when no explicit area', function (): void {
    config()->set('seo.country');

    $schema = Organization::make(
        url: 'https://example.com',
        name: 'Example Org',
        author: 'Marco Azzari',
        logo: url('/images/logo.webp'),
    )->toArray();

    expect($schema)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => 'https://example.com/#organization',
        'url' => 'https://example.com',
        'name' => 'Example Org',
        'logo' => 'http://localhost/images/logo.webp',
        'founder' => [
            '@type' => 'Person',
            '@id' => 'https://www.marcoazzari.com/#person',
            'name' => 'Marco Azzari',
        ],
        'areaServed' => [
            '@type' => 'Place',
            'name' => 'World',
        ],
    ]);
});

it('builds Organization schema payload with explicit city area', function (): void {
    config()->set('seo.country');

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
