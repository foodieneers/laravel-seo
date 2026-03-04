<?php

use Foodieneers\Laravel\SEO\Support\SEOData;

it('renders nothing if no schema is provided', function (): void {
    $output = renderSeo(new SEOData());

    expect($output)
        ->not->toContain('schema.org');

    $output = renderSeo(new SEOData(schema: []));

        expect($output)
            ->not->toContain('schema.org');
});

it('renders BreadcrumbList schema', function (): void {
    $output = renderSeo(new SEOData(
        schema: ['BreadcrumbList'],
        breadcrumbs: [
            'Home' => '/',
            'Category' => '/category',
        ],
        currentBreadcrumbName: 'Product',
    ));

    expect($output)
        ->toContain('schema.org');
});