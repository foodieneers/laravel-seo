<?php

namespace Foodieneers\Laravel\SEO\Tests;

use Foodieneers\Laravel\SEO\LaravelSEOServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName): string => 'Foodieneers\\Laravel\\SEO\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );

        // Freeze the time across entire testsuite...
        Date::setTestNow(now());
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelSEOServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        Schema::enableForeignKeyConstraints();

        (include __DIR__ . '/../database/migrations/create_seo_table.php.stub')->up();
        (include __DIR__ . '/../tests/Fixtures/migrations/create_pages_table.php')->up();
    }
}
