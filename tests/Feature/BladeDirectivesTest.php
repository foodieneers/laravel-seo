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

it('throws when @seoData is rendered twice', function (): void {
    expect(fn () => Blade::render(<<<'BLADE'
@seo(title: 'Directive Title')
@seoData
@seoData
BLADE))
        ->toThrow(\Illuminate\View\ViewException::class, 'SEOService can only be rendered once per request.');
});
