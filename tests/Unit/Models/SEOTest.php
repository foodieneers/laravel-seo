<?php

use Carbon\CarbonImmutable;
use Foodieneers\Laravel\SEO\Models\SEO;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\Tests\Fixtures\Page;
use Foodieneers\Laravel\SEO\Tests\Fixtures\PageWithoutTitleSuffixFunction;
use Foodieneers\Laravel\SEO\Tests\Fixtures\PageWithoutTitleSuffixProperty;
use Illuminate\Support\Facades\Date;

it('can morph a model to the SEO model', function (): void {
    $page = Page::create();

    expect($page->seo)->toBeInstanceOf(SEO::class);
});

it('can prepare the SEO for use on a page', function (): void {
    $seo = Page::create()->seo;

    $export = $seo->prepareForUsage();

    expect($export)->toBeInstanceOf(SEOData::class);
});

it('can have immutable timestamps', function (): void {
    Date::useClass(CarbonImmutable::class);

    $seo = Page::create()->seo;

    $export = $seo->prepareForUsage();

    expect($export)->toBeInstanceOf(SEOData::class);
});

it('can add properties to a SEO model', function (string $property, string $input): void {
    $page = Page::create();

    $page->seo->{$property} = $input;
    $page->push();

    expect($page->refresh()->seo)
        ->{$property}->toBe($input);

    expect($page->seo->prepareForUsage())
        ->{$property}->toBe($input);
})->with([
    ['description', 'This is a description'],
    ['title', 'My Cool Page Title'],
]);

it('can override certain SEO Data', function (string $overriddenProperty, string $input): void {
    $page = Page::create();

    $page->seo->update(
        $defaults = [
            'title' => 'Default title',
            'description' => 'Default description',
        ]
    );

    $page::$overrides = [
        $overriddenProperty => 'Custom override',
    ];

    $page->seo->{$overriddenProperty} = $input;
    $page->push();

    expect($page->refresh()->seo)
        ->{$overriddenProperty}->toBe($input);

    expect($page->seo->prepareForUsage())
        ->{$overriddenProperty}->toBe('Custom override');

    foreach (collect($defaults)->except($overriddenProperty) as $property => $value) {
        expect($page->seo->prepareForUsage())
            ->{$property}->toBe($value);
    }
})->with([
    ['description', 'This is a description'],
    ['title', 'My Cool Page Title'],
]);

it('can give the title of a page a suffix it was specified', function (): void {
    config()->set('seo.title.suffix', ' | TestCases');

    $seo = Page::create()->seo;

    $seo->update([
        'title' => 'My page title',
    ]);

    expect($seo->prepareForUsage())
        ->enableTitleSuffix->toBeTrue();
});

it('can disable the suffix in the page model', function (): void {
    config()->set('seo.title.suffix', ' | TestCases');

    $page = PageWithoutTitleSuffixProperty::create();

    $page->seo->update([
        'title' => 'My page title',
    ]);

    expect($page->seo->prepareForUsage())
        ->title->toBe('My page title');
});

it('can disable the suffix in the page model dynamically via a function', function (): void {
    config()->set('seo.title.suffix', ' | TestCases');

    $page = PageWithoutTitleSuffixFunction::create();

    $page->seo->update([
        'title' => 'My page title',
    ]);

    expect($page->seo->prepareForUsage())
        ->title->toBe('My page title');
});
