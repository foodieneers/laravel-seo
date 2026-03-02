<?php

use Foodieneers\Laravel\SEO\Facades\SEOManager;
use Foodieneers\Laravel\SEO\Support\MetaTag;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\TagManager;
use Illuminate\Support\Collection;

use function Pest\Laravel\get;

it('can replace the title if we\'re on the homepage', function (?string $homepageTitleConfig, string $expectedTitle): void {
    config()->set('seo.title.homepage_title', $homepageTitleConfig);
    config()->set('seo.title.suffix', '| My Website suffix');

    get(route('seo.test-home'))
        ->assertSee($expectedTitle);
})->with([
    [null, '| My Website suffix'],
    ['Custom homepage title', 'Custom homepage title'],
]);

test('can render the SEOData from an object that\'s directly passed in', function (): void {
    $SEOData = new SEOData(
        title: 'Awesome News - My Project',
    );

    $output = resolve(TagManager::class)->for($SEOData)->render();

    expect($output)->toContain('Awesome News - My Project');
});

it('can pipe the SEOData through the transformer before putting it into the collection', function (): void {
    config()->set('seo.title.infer_title_from_url', true);

    get(route('seo.test-plain'))
        ->assertSee('<title>Test Plain</title>', false);

    SEOManager::SEODataTransformer(function (SEOData $SEOData): SEOData {
        $SEOData->title = 'Transformed Title';

        return $SEOData;
    });

    SEOManager::SEODataTransformer(function (SEOData $SEOData): SEOData {
        $SEOData->description = 'Transformed description';

        return $SEOData;
    });

    get(route('seo.test-plain'))
        ->assertSee('<title>Transformed Title</title>', false)
        ->assertSee('Transformed description');
});

it('can pipe the generated tags through the transformers just before render', function (): void {
    SEOManager::tagTransformer(fn (Collection $tags): Collection => $tags->push(new MetaTag('test', 'content')));

    get(route('seo.test-plain'))
        ->assertSee('<meta name="test" content="content">', false);
});
