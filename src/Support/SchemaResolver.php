<?php

namespace Foodieneers\Laravel\SEO\Support;

use Spatie\SchemaOrg\Graph;

class SchemaResolver
{
    /**
     * @return list<array<string, mixed>>|null
     */
    public static function resolve(SEOData $source): ?array
    {
        if ($source->schema === []) {
            return null;
        }

        $schemaCollection = [];
        $structuredSchemas = [];

        foreach ($source->schema as $item) {
            if ($item instanceof Graph) {
                /** @var array<string, mixed> $graphPayload */
                $graphPayload = $item->toArray();
                $schemaCollection[] = $graphPayload;

                continue;
            }

            $structuredSchemas[] = $item;
        }

        if (count($structuredSchemas) === 1) {
            $schemaCollection[] = $structuredSchemas[0]->toArray();
        } elseif (count($structuredSchemas) > 1) {
            $graph = new Graph;

            foreach ($structuredSchemas as $structuredSchema) {
                $graph->add($structuredSchema);
            }

            $schemaCollection[] = $graph->toArray();
        }

        return $schemaCollection;
    }
}
