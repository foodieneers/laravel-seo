<?php

use Foodieneers\Laravel\SEO\Schema\BreadcrumbList;

it('builds BreadcrumbList schema items in order', function (): void {
    $schema = BreadcrumbList::make(
        breadcrumbs: [
            'Home' => '/',
            'Category' => '/category',
        ],
        currentBreadcrumbName: 'Product',
        appendBreadcrumb: [
            'Offer' => 'https://example.com/offer',
        ],
    )->toArray();

    expect($schema)
        ->toMatchArray([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => url('/'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Category',
                    'item' => url('/category'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => 'Product',
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 4,
                    'name' => 'Offer',
                    'item' => 'https://example.com/offer',
                ],
            ],
        ]);
});
