<?php

namespace Foodieneers\Laravel\SEO\Support;

use Foodieneers\Laravel\SEO\SchemaCollection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\BreadcrumbList;
use Spatie\SchemaOrg\Graph;
use Spatie\SchemaOrg\Schema;

class SchemaResolver
{
    public function __construct(public array $sourceSchema, public SEOInputData $source) {}

    public static function resolve(SEOInputData $source): ?SchemaCollection
    {
        if ($source->schema === []) {
            return null;
        }

        return (new self($source->schema, $source))->buildSchema();
    }

    protected function buildSchema(): SchemaCollection
    {
        $schemaCollection = SchemaCollection::initialize();
        $structuredSchemas = [];

        foreach ($this->sourceSchema as $schemaType) {
            $resolvedSchema = $this->calculateSchema($schemaType);

            if ($resolvedSchema instanceof BaseType) {
                $structuredSchemas[] = $resolvedSchema;
            } else {
                $schemaCollection->add($resolvedSchema);
            }
        }

        if (count($structuredSchemas) === 1) {
            return $schemaCollection->add($structuredSchemas[0]->toArray());
        }

        if (count($structuredSchemas) > 1) {
            $graph = new Graph;
            foreach ($structuredSchemas as $item) {
                $graph->add($item);
            }

            return $schemaCollection->add($graph->toArray());
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

    protected function buildBreadcrumbList(SEOInputData $source): BreadcrumbList
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
            ->name($this->resolveCurrentBreadcrumbName($source));

        foreach ($source->appendBreadcrumb ?? [] as $name => $url) {
            $list[] = Schema::listItem()
                ->position($counter++)
                ->name($name)
                ->item($url);
        }

        return Schema::breadcrumbList()
            ->itemListElement($list);
    }

    protected function resolveCurrentBreadcrumbName(SEOInputData $source): string
    {
        return $source->currentBreadcrumbName
            ?? Str::of($source->url ?: url()->current())->afterLast('/')->headline()->toString();
    }
}
