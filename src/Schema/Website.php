<?php

namespace Foodieneers\Laravel\SEO\Schema;

use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\WebSite as SchemaWebSite;

class Website
{
    public static function make(string $url, string $name, ?string $author = null): SchemaWebSite
    {
        $schema = Schema::website()
            ->identifier($url . '/#website')
            ->url($url)
            ->name($name);

        if ($author !== null) {
            $schema->author(Person::getAuthor($author));
        }

        return $schema;
    }
}
