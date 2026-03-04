<?php

namespace Foodieneers\Laravel\SEO\Schema;

use Exception;
use Spatie\SchemaOrg\Person as SchemaPerson;
use Spatie\SchemaOrg\Schema;

class Person
{
    public static function make(string $author): ?SchemaPerson
    {
        if ($author === 'Marco Azzari') {
            return Schema::person()
                ->name($author)
                ->id('https://www.marcoazzari.com/#person')
                ->url('https://www.marcoazzari.com')
                ->jobTitle('Founder')
                ->description('Founder of Food Explorers and Culinary Expert')
                ->worksFor(Schema::organization()
                    ->name('Food Explorers')
                    ->url('https://www.foodexplorers.ch')
                    ->logo('https://www.foodexplorers.ch/logo.png')
                )
                ->sameAs([
                    'https://www.linkedin.com/in/marcoazzari/',
                    'https://www.x.com/marcoazzari',
                    'https://substack.com/@marcoazzari',
                    'https://www.instagram.com/marco.azzari/',
                    'https://www.youtube.com/@marco.azzari',
                ])
                ->knowsAbout([
                    'Gastronomy',
                    'Culinary',
                    'Italian Food',
                    'Pizza',
                    'Wine & Spirits',
                    'Zurich Food Scene',
                    'Milano Food Scene',
                ]);
        }
        throw new Exception("Author [{$author}] not found");
    }
}
