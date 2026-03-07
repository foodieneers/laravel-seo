<?php

namespace Foodieneers\Laravel\SEO\Support;

use Foodieneers\Laravel\SEO\Schema\BlogPost;
use Foodieneers\Laravel\SEO\Schema\BreadcrumbList;
use Foodieneers\Laravel\SEO\Schema\Organization;
use Foodieneers\Laravel\SEO\Schema\Person;
use Foodieneers\Laravel\SEO\Schema\Website;
use InvalidArgumentException;
use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\Graph;

class SchemaResolver
{
    public function __construct(public SEOData $source) {}

    /**
     * @return list<array<string, mixed>>|null
     */
    public static function resolve(SEOData $source): ?array
    {
        if ($source->schema === []) {
            return null;
        }

        return (new self($source))->buildSchema();
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function buildSchema(): array
    {
        $schemaCollection = [];
        $structuredSchemas = [];

        foreach ($this->source->schema as $schemaType) {
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
            'BreadcrumbList' => BreadcrumbList::make($this->source->breadcrumbs, $this->source->currentBreadcrumbName, $this->source->appendBreadcrumb),
            'Person' => Person::make($this->source->author),
            'Website' => Website::make($this->source->url, $this->source->site_name, $this->source->author, $this->source->hasOrganization()),
            'Organization' => Organization::make($this->source->url, $this->source->site_name, $this->source->author, $this->source->area),
            'BlogPost' => BlogPost::make($this->source->url, $this->source->title, $this->source->image, $this->source->description, $this->source->published_at, $this->source->modified_at, $this->source->author, $this->source->hasOrganization()),
            default => throw new InvalidArgumentException("Unsupported schema type [{$schemaType}]"),
        };
    }
}
