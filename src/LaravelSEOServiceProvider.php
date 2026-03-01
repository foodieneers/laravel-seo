<?php

namespace Foodieneers\Laravel\SEO;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSEOServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-seo')
            ->hasConfigFile()
            ->hasViews('seo');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(SEOManager::class);
    }
}
