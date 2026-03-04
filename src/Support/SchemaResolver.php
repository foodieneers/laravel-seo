<?php

namespace Foodieneers\Laravel\SEO\Support;

use InvalidArgumentException;
use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\BreadcrumbList;
use Spatie\SchemaOrg\Graph;
use Spatie\SchemaOrg\Schema;

class SchemaResolver
{
    public function __construct(public array $sourceSchema, public SEOData $source) {}

    /**
     * @return list<array<string, mixed>>|null
     */
    public static function resolve(SEOData $source): ?array
    {
        if ($source->schema === []) {
            return null;
        }

        return (new self($source->schema, $source))->buildSchema();
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function buildSchema(): array
    {
        $schemaCollection = [];
        $structuredSchemas = [];

        foreach ($this->sourceSchema as $schemaType) {
            $resolvedSchema = $this->calculateSchema($schemaType);

            if ($resolvedSchema instanceof BaseType) {
                $structuredSchemas[] = $resolvedSchema;
            } else {
                $schemaCollection[] = $resolvedSchema;
            }
        }

        if (count($structuredSchemas) === 1) {
            $schemaCollection[] = $structuredSchemas[0]->toArray();

            return $schemaCollection;
        }

        if (count($structuredSchemas) > 1) {
            $graph = new Graph;
            foreach ($structuredSchemas as $item) {
                $graph->add($item);
            }

            $schemaCollection[] = $graph->toArray();
        }

        return $schemaCollection;
    }

    /**
     * @return BaseType|array<string, mixed>
     */
    protected function calculateSchema(mixed $schemaType): BaseType | array
    {
        if (is_array($schemaType)) {
            return $schemaType;
        }

        throw_unless(is_string($schemaType), InvalidArgumentException::class, 'Schema type must be a string or array');

        return match ($schemaType) {
            'BreadcrumbList' => $this->buildBreadcrumbList($this->source),
            default => throw new InvalidArgumentException("Unsupported schema type [{$schemaType}]"),
        };
    }

    protected function buildBreadcrumbList(SEOData $source): BreadcrumbList
    {
        $list = [];
        $counter = 1;

        foreach ($source->breadcrumbs ?? [] as $name => $url) {
            $list[] = Schema::listItem()
                ->position($counter++)
                ->name($name)
                ->item($url);
        }

        $list[] = Schema::listItem()
            ->position($counter++)
            ->name($this->source->currentBreadcrumbName);

        foreach ($source->appendBreadcrumb ?? [] as $name => $url) {
            $list[] = Schema::listItem()
                ->position($counter++)
                ->name($name)
                ->item($url);
        }

        return Schema::breadcrumbList()
            ->itemListElement($list);
    }

}
