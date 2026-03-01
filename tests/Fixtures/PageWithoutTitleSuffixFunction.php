<?php

namespace Foodieneers\Laravel\SEO\Tests\Fixtures;


use Foodieneers\Laravel\SEO\Support\HasSEO;

class PageWithoutTitleSuffixFunction extends Model
{
    use HasSEO;

    public bool $enableTitleSuffix = true;

    protected $guarded = [];

    protected $table = 'pages';

    public function enableTitleSuffix(): bool
    {
        return false;
    }
}
