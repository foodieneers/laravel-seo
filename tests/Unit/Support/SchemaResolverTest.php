<?php

use Foodieneers\Laravel\SEO\Support\SchemaResolver;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Spatie\SchemaOrg\Graph;
use Spatie\SchemaOrg\Schema;

it('returns null when schema is empty', function (): void {
    expect(SchemaResolver::resolve(new SEOData))->toBeNull()
        ->and(SchemaResolver::resolve(new SEOData(schema: [])))->toBeNull();
});

it('resolves a single BaseType schema', function (): void {
    $product = Schema::product()->name('Custom Product');

    $resolved = SchemaResolver::resolve(new SEOData(schema: [$product]));

    expect($resolved)->toHaveCount(1)
        ->and($resolved[0])->toMatchArray([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => 'Custom Product',
        ]);
});

it('merges multiple BaseTypes into a graph', function (): void {
    $organization = Schema::organization()->name('Example Site');
    $product = Schema::product()->name('Addon');

    $resolved = SchemaResolver::resolve(new SEOData(schema: [$organization, $product]));

    expect($resolved)->toHaveCount(1)
        ->and($resolved[0])->toHaveKey('@graph')
        ->and(collect($resolved[0]['@graph'])->pluck('@type')->all())
        ->toContain('Organization', 'Product');
});

it('passes through a Graph instance', function (): void {
    $graph = new Graph;
    $graph->add(Schema::product()->name('In graph'));

    $resolved = SchemaResolver::resolve(new SEOData(schema: [$graph]));

    expect($resolved)->toHaveCount(1)
        ->and($resolved[0])->toHaveKey('@graph')
        ->and($resolved[0]['@graph'][0]['name'])->toBe('In graph');
});

it('keeps Graph instances separate from merged BaseTypes', function (): void {
    $graph = new Graph;
    $graph->add(Schema::product()->name('In graph'));
    $organization = Schema::organization()->name('Example Site');

    $resolved = SchemaResolver::resolve(new SEOData(schema: [$graph, $organization]));

    expect($resolved)->toHaveCount(2)
        ->and($resolved[0])->toHaveKey('@graph')
        ->and($resolved[1])->toMatchArray([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Example Site',
        ]);
});

it('throws for raw array schema items', function (): void {
    new SEOData(schema: [[
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
    ]]);
})->throws(InvalidArgumentException::class, 'Schema must be a BaseType or Graph.');

it('throws for string schema items', function (): void {
    new SEOData(schema: ['Website']);
})->throws(InvalidArgumentException::class, 'Schema must be a BaseType or Graph.');
