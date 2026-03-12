<?php

use Illuminate\Support\Facades\Blade;

use function Pest\Laravel\artisan;

beforeEach(function (): void {
    artisan('view:clear');
});

it('renders SEO tags through @seo and @seoData directives', function (): void {
    config()->set('seo.site_name', 'Fallback Site');

    $output = Blade::render(<<<'BLADE'
@seo(title: 'Directive Title', description: 'Directive Description')
@seoData
BLADE);

    expect($output)
        ->toContain('Directive Title')
        ->toContain('Directive Description');
});
