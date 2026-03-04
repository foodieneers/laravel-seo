<?php

namespace Foodieneers\Laravel\SEO\Schema;

use Spatie\SchemaOrg\BreadcrumbList as SchemaBreadcrumbList;
use Spatie\SchemaOrg\Schema;

class BreadcrumbList
{
  
    public static function make(array $breadcrumbs, string $currentBreadcrumbName, array $appendBreadcrumb = []): SchemaBreadcrumbList
    {
        $list = [];
        $counter = 1;
        foreach ($breadcrumbs as $name => $url) {
            $list[] = Schema::listItem()
                ->position($counter++)
                ->name($name)
                ->item(url($url));
        }
        $list[] = Schema::listItem()
            ->position($counter++)
            ->name($currentBreadcrumbName);
        foreach ($appendBreadcrumb ?? [] as $name => $url) {
            $list[] = Schema::listItem()
                ->position($counter++)
                ->name($name)
                ->item($url);
        }
        return Schema::breadcrumbList()
            ->itemListElement($list);
    }
}