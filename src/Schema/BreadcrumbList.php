<?php

namespace Foodieneers\Laravel\SEO\Schema;

use Spatie\SchemaOrg\BreadcrumbList as SchemaBreadcrumbList;
use Spatie\SchemaOrg\Schema;

class BreadcrumbList
{
    /**
     * @param  array<string, string>  $breadcrumbs
     * @param  array<string, string>  $appendBreadcrumb
     */
    public static function make(array $breadcrumbs, string $currentBreadcrumbName, array $appendBreadcrumb = []): SchemaBreadcrumbList
    {
        $list = [];
        $counter = 1;
        foreach ($breadcrumbs as $name => $url) {
            $list[] = Schema::listItem()
                ->position($counter++)
                ->name($name)
                ->item(Schema::thing()->identifier(url($url)));
        }
        $list[] = Schema::listItem()
            ->position($counter++)
            ->name($currentBreadcrumbName)
            ->item(Schema::thing()->identifier(url()->current()));
       
        foreach ($appendBreadcrumb as $name => $url) {
            $list[] = Schema::listItem()
                ->position($counter++)
                ->name($name)
                ->item(Schema::thing()->identifier(url($url)));
        }

        return Schema::breadcrumbList()
            ->itemListElement($list);
    }
}
