<?php

namespace Foodieneers\Laravel\SEO\Schema;

use RalphJSmit\Helpers\Laravel\Pipe\Pipeable;
use Foodieneers\Laravel\SEO\Support\Tag;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;

class CustomSchema extends Tag
{
    use Pipeable;

    public string $tag = 'script';

    public array $attributes = [
        'type' => 'application/ld+json',
    ];

    public function __construct(iterable | Arrayable $inner)
    {
        $this->inner = new HtmlString(
            collect($inner)->toJson(JSON_UNESCAPED_SLASHES)
        );
    }
}
