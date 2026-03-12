<?php

use Foodieneers\Laravel\SEO\SEOService;
use Foodieneers\Laravel\SEO\Support\SEOData;

test('the SEOService singleton works as expected', function (): void {
    $serviceA = resolve(SEOService::class);
    $serviceB = resolve(SEOService::class);

    expect($serviceA)->toBe($serviceB);
});

it('renders fallback html when no data is set', function (): void {
    config()->set('seo.site_name', 'Fallback Site Name');

    $output = resolve(SEOService::class)->render();

    expect($output)
        ->toContain('<title>Fallback Site Name</title>')
        ->toContain('<meta name="robots" content="noindex, nofollow, noarchive">');
});

it('renders set data', function (): void {
    $service = resolve(SEOService::class);
    $service->setData(new SEOData(
        title: 'Service Rendered Title',
        description: 'Service Description',
    ));

    expect($service->hasData())->toBeTrue();

    $output = $service->render();
    expect($output)
        ->toContain('Service Rendered Title')
        ->toContain('Service Description');
});
