<?php

namespace Foodieneers\Laravel\SEO\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class SchemaTagCollection extends Collection implements Renderable
{
    public static function initialize(?SEOData $SEOData = null): ?static
    {
        if (! $SEOData instanceof SEOData) {
            return null;
        }

        $schemas = SchemaResolver::resolve($SEOData);

        if ($schemas === null || $schemas === []) {
            return null;
        }

        return new static($schemas);
    }

    public function render(): string
    {
        return $this
            ->map(function (array $schema): string {
                $json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
                $json = str_replace('</script', '<\/script', $json);

                return sprintf('<script type="application/ld+json">%s</script>', $json);
            })
            ->implode(PHP_EOL);
    }
}
