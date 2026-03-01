<?php

use function Pest\Laravel\get;

beforeEach(function (): void {
    copy(__DIR__ . '/../../Fixtures/images/favicon.ico', public_path('favicon.ico'));
});

it('will not render the favicon if the favicon is set to null', function (): void {
    config()->set('seo.favicon');

    get(route('seo.test-plain'))
        ->assertDontSee('link rel="shortcut icon"');
});

it('will render the favicon if the favicon is set', function (): void {
    config()->set('seo.favicon', 'favicon.ico');

    get(route('seo.test-plain'))
        ->assertSee('<link href="' . secure_url('favicon.ico') . '" rel="shortcut icon">', false);
});
