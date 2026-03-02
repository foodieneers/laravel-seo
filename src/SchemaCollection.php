<?php

namespace Foodieneers\Laravel\SEO;

use Illuminate\Support\Collection;

class SchemaCollection extends Collection
{
    public array $markup = [];

    public static function initialize(): static
    {
        return new static;
    }
}
