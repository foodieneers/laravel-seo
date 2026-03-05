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
                ->jobTitle('Founder & Gastronomy Expert')
                ->description('Founder of Food Explorers, Explorers Universe')
                ->worksFor(Schema::organization()
                    ->name('Explorers Universe')
                    ->url('https://www.explorersuniverse.com')
                    ->logo('https://www.foodexplorers.com/logo.webp')
                )
                ->brand([
                    Schema::brand()
                        ->name('Food Explorers')
                        ->url('https://www.foodexplorers.ch')
                        ->logo('https://www.foodexplorers.ch/logo.webp'),
                    Schema::brand()
                        ->name('Analytical Drinker')
                        ->url('https://www.analyticaldrinker.com')
                        ->logo('https://www.analyticaldrinker.com/logo.webp'),
                ])
                ->sameAs([
                    'https://www.linkedin.com/in/marcoazzari/',
                    'https://www.x.com/marcoazzari',
                    'https://substack.com/@marcoazzari',
                    'https://www.instagram.com/marco.azzari/',
                    'https://www.youtube.com/@marco.azzari',
                ])
                ->knowsAbout([
                    'Gastronomy',
                    'Culinary Arts',
                    'Italian Cuisine',
                    'Pizza',
                    'Wine & Spirits Tasting',
                    'Zurich Food Scene',
                    'Milano Food Scene',
                ]);
        }
        throw new Exception("Author [{$author}] not found");
    }

    public static function getAuthor(string $author): SchemaPerson
    {
        if ($author === 'Marco Azzari') {
            return Schema::person()
                ->name('Marco Azzari')
                ->id('https://www.marcoazzari.com/#person');
        }
        throw new Exception("Author [{$author}] not found");
    }
}
