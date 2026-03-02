<?php

namespace Foodieneers\Laravel\SEO\Tests;

use Foodieneers\Laravel\SEO\LaravelSEOServiceProvider;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

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
    }
}
