<?php

namespace Foodieneers\Laravel\SEO;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSEOServiceProvider extends PackageServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->singleton(SEOService::class, fn (): SEOService => new SEOService);
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-seo')
            ->hasConfigFile();
    }

    public function bootingPackage(): void
    {
        Blade::directive('seo', fn ($expression): string => "<?php app(\Foodieneers\Laravel\SEO\SEOService::class)->setData(new \Foodieneers\Laravel\SEO\Support\SEOInputData({$expression})); ?>");
        Blade::directive('seoData', fn (): string => "<?php echo app(\Foodieneers\Laravel\SEO\SEOService::class)->render(); ?>");
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(SEOManager::class);
    }
}
