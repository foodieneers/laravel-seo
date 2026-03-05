<?php

use Foodieneers\Laravel\SEO\Support\SEOData;

it('renders nothing if no schema is provided', function (): void {
    $output = renderSeo(new SEOData);

    expect($output)
        ->not->toContain('schema.org');

    $output = renderSeo(new SEOData(schema: []));

    expect($output)
        ->not->toContain('schema.org');
});

it('renders BreadcrumbList schema', function (): void {
    $output = renderSeo(new SEOData(
        schema: ['BreadcrumbList'],
        currentBreadcrumbName: 'Product',
        breadcrumbs: [
            'Home' => '/',
            'Category' => '/category',
        ],
    ));

    expect($output)
        ->toContain('<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":{"@type":"Thing","@id":"http://localhost"}},{"@type":"ListItem","position":2,"name":"Category","item":{"@type":"Thing","@id":"http://localhost/category"}},{"@type":"ListItem","position":3,"name":"Product"}]}</script>');
});

it('renders Website schema', function (): void {
    $output = renderSeo(new SEOData(
        url: 'https://example.com',
        site_name: 'Example Site',
        schema: ['Website'],
    ));

    expect($output)
        ->toContain('{"@context":"https://schema.org","@type":"WebSite","url":"https://example.com","name":"Example Site","author":{"@type":"Person","name":"Marco Azzari","@id":"https://www.marcoazzari.com/#person"},"@id":"https://example.com/#website"}</script>');
});
