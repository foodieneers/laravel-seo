<?php

use Foodieneers\Laravel\SEO\Tests\Fixtures\Page;
use Foodieneers\Laravel\SEO\Tests\TestCase;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\withoutExceptionHandling;

uses(TestCase::class)
    ->beforeEach(function (): void {
        withoutExceptionHandling();

        Route::middleware('web')->group(function (): void {
            Route::get('/', fn (): string => (string) seo())->name('seo.test-home');
            Route::get('/seo/test-plain', fn (): string => (string) seo())->name('seo.test-plain');
            Route::get('/seo/{page}', fn (Page $page): string => (string) seo()->for($page))->name('seo.test-page');
        });

        Page::$overrides = [];
    })
    ->in(__DIR__);
