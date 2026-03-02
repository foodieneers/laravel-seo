<?php

use Foodieneers\Laravel\SEO\SEOService;
use Foodieneers\Laravel\SEO\Support\SEOInputData;

it('can render fallback HTML when no SEOInputData is set', function (): void {
    expect(seo())
        ->toContain('<meta name="robots" content="noindex, nofollow, noarchive">');
});

it('can render input data from the SEO service', function (): void {
    app(SEOService::class)->setData(new SEOInputData(
        title: 'Awesome News - My Project',
        description: 'Lorem Ipsum',
    ));

    expect(seo())
        ->toBeString()
        ->toContain('Awesome News - My Project');
});
