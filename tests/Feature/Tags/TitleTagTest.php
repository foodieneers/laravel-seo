<?php

use Foodieneers\Laravel\SEO\Tags\TitleTag;
use Foodieneers\Laravel\SEO\Tests\Fixtures\Http\Middleware\DummyInertiaMiddleware;
use Foodieneers\Laravel\SEO\Tests\Fixtures\Page;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\get;

it('will not infer the title from the url if that isn\'t allowed', function (): void {
    config()->set('seo.title.infer_title_from_url', false);

    get(route('seo.test-plain'))
        ->assertDontSee('<title>');
});

it('will infer the title from the url if that is allowed', function (): void {
    config()->set('seo.title.infer_title_from_url', true);
    config()->set('seo.title.suffix', ' | Laravel SEO');

    get(route('seo.test-plain'))
        ->assertSee('<title>Test Plain | Laravel SEO</title>', false);
});

it('will display the title if the associated SEO model has a title', function (): void {
    $page = Page::create();

    $page->seo->update([
        'title' => 'My great title, set by a model on a per-page basis. ', // <-- Notice the space at the end, that one should be trimmed.
    ]);

    $page->refresh();

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<title>My great title, set by a model on a per-page basis.</title>', false);
});

it('will infer the title from the url if that is allowed and the model doesn\'t return a title', function (): void {
    config()->set('seo.title.infer_title_from_url', true);
    config()->set('seo.title.suffix', ' | Laravel SEO');

    $page = Page::create();

    $page->seo->update([
        'title' => null,
    ]);

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<title>1 | Laravel SEO</title>', false);
});

it('will escape the title', function (): void {
    config()->set('seo.title.infer_title_from_url', true);
    config()->set('seo.title.suffix', ' - A & B');

    get(route('seo.test-plain'))
        ->assertSee('<title>Test Plain - A &amp; B</title>', false);
});

it('will not include the `inertia` attribute by default', function (): void {
    config()->set('seo.title.infer_title_from_url', true);
    config()->set('seo.title.suffix', ' - A & B');

    $titleTag = new TitleTag('X');

    expect($titleTag)
        ->collectAttributes()->all()->toBeEmpty();

    $route = Arr::random(Route::getRoutes()->getRoutes());

    Route::partialMock()
        ->shouldReceive('current')
        ->andReturn($route);

    Route::partialMock()
        ->shouldReceive('gatherRouteMiddleware')
        ->withAnyArgs()
        ->andReturn([
            StartSession::class,
        ]);

    $titleTag = new TitleTag('X');

    expect($titleTag)
        ->collectAttributes()->all()->toBeEmpty()
        ->render()->__toString()->toBe('<title>X</title>');
});

it('will include the `inertia` attribute', function (): void {
    config()->set('seo.title.infer_title_from_url', true);
    config()->set('seo.title.suffix', ' - A & B');

    $titleTag = new TitleTag('X');

    expect($titleTag)
        ->collectAttributes()->all()->toBeEmpty();

    $route = Arr::random(Route::getRoutes()->getRoutes());

    Route::partialMock()
        ->shouldReceive('current')
        ->andReturn($route);

    Route::partialMock()
        ->shouldReceive('gatherRouteMiddleware')
        ->withAnyArgs()
        ->andReturn([
            StartSession::class,
            DummyInertiaMiddleware::class,
        ]);

    $titleTag = new TitleTag('X');

    expect($titleTag)
        ->collectAttributes()->all()->toBe([
            'inertia' => true,
        ])
        ->render()->__toString()->toBe('<title inertia>X</title>');
});
