<?php

namespace Foodieneers\Laravel\SEO\Tests\Fixtures;


use Foodieneers\Laravel\SEO\Support\HasSEO;

class PageWithOverrides extends Model
{
    use HasSEO;

    protected $guarded = [];

    protected $table = 'pages';

    public static array $overrides = [];
}
