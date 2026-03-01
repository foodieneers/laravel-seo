<?php

namespace Foodieneers\Laravel\SEO;

use Illuminate\Support\Facades\Blade;
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

    public function bootingPackage(): void
    {
        Blade::directive('seo', fn ($expression): string => "<?php \$seo = new \Foodieneers\Laravel\SEOInputData({$expression}); ?>");
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(SEOManager::class);
    }
}
