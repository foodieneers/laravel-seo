<?php

namespace Foodieneers\Laravel\SEO\Schema;

use Spatie\SchemaOrg\City;
use Spatie\SchemaOrg\Organization as SchemaOrganization;
use Spatie\SchemaOrg\Schema;

class Organization
{
    public static function make(string $url, string $name, ?string $author = null, ?string $area = null, ?string $logo = null): SchemaOrganization
    {
        $organization = Schema::organization()
            ->identifier($url . '/#organization')
            ->url($url)
            ->name($name)
            ->logo($logo)
            ->founder(Person::getAuthor($author));

        if ($area !== null) {
            $organization->areaServed(self::getArea($area));
        }

        return $organization;
    }

    public static function getArea(string $area): City
    {
        return Schema::city()->name($area);
    }
}
