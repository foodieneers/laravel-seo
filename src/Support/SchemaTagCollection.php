<?php

namespace Foodieneers\Laravel\SEO\Support;

use Foodieneers\Laravel\SEO\SchemaCollection;
use Foodieneers\Laravel\SEO\Schema\CustomSchema;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class SchemaTagCollection extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(?SEOData $SEOData = null): ?static
    {
        $schemas = $SEOData?->schema;

        if (!$schemas instanceof SchemaCollection) {
            return null;
        }

        $collection = new static;

        foreach ($schemas as $schema) {
            $collection->push(new CustomSchema(value($schema, $SEOData)));
        }

        foreach ($schemas->markup as $markupClass => $markupBuilders) {
            $collection->push(new $markupClass($SEOData, $markupBuilders));
        }

        return $collection;
    }
}
