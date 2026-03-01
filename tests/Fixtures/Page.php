<?php

namespace Foodieneers\Laravel\SEO\Tests\Fixtures;

use Foodieneers\Laravel\SEO\Support\HasSEO;
use Foodieneers\Laravel\SEO\Support\SEOData;

class Page extends Model
{
    use HasSEO;

    public bool $enableTitleSuffix = true;

    protected $guarded = [];

    protected $table = 'pages';

    public static array $overrides = [];

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(...$this::$overrides);
    }
}
