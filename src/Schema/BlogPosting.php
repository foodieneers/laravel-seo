<?php

namespace Foodieneers\Laravel\SEO\Schema;

use Carbon\CarbonInterface;
use Spatie\SchemaOrg\BlogPosting as SchemaBlogPost;
use Spatie\SchemaOrg\Schema;

class BlogPosting
{
    public static function make(string $url, string $headline, ?string $image = null, string $description = '', ?CarbonInterface $datePublished = null, ?CarbonInterface $dateModified = null, ?string $author = null, bool $hasPublisher = false): SchemaBlogPost
    {
        $schema = Schema::blogPosting()
            ->identifier($url . '/#blogpost')
            ->url($url)
            ->headline($headline)
            ->description($description)
            ->mainEntityOfPage($url)
            ->datePublished($datePublished);

        if ($image !== null) {
            $schema->image($image);
        }

        if ($dateModified instanceof CarbonInterface) {
            $schema->dateModified($dateModified);
        }

        if ($author !== null) {
            $schema->author(Person::getAuthor($author));
        }

        if ($hasPublisher) {
            $schema->publisher(Schema::organization()->identifier(url('/#organization')));
        }

        return $schema;
    }
}
