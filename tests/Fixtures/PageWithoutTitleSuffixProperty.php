<?php

namespace Foodieneers\Laravel\SEO\Tests\Fixtures;


use Foodieneers\Laravel\SEO\Support\HasSEO;

class PageWithoutTitleSuffixProperty extends Model
{
    use HasSEO;

    public bool $enableTitleSuffix = false;

    protected $guarded = [];

    protected $table = 'pages';
}
