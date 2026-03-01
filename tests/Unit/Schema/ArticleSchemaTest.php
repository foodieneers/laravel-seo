<?php

use Foodieneers\Laravel\SEO\Schema\ArticleSchema;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Collection;

beforeEach(function (): void {
    $this->SEOData = new SEOData(
        title: 'Test',
        description: 'Description',
        author: 'Ralph J. Smit',
        image: 'https://example.com/image.jpg',
        url: 'https://example.com/test',
        published_time: now()->subDays(3),
        modified_time: now(),
        articleBody: '<p>Test</p>',
    );
});

it('can construct Schema Markup: Article', function (): void {
    $articleSchema = new ArticleSchema($this->SEOData);

    expect((string) $articleSchema->render())
        ->toBe(
            '<script type="application/ld+json">' .
                $string = json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => 'https://example.com/test',
                    ],
                    'datePublished' => now()->subDays(3)->toIso8601String(),
                    'dateModified' => now()->toIso8601String(),
                    'headline' => 'Test',
                    'author' => [
                        '@type' => 'Person',
                        'name' => 'Ralph J. Smit',
                    ],
                    'description' => 'Description',
                    'image' => 'https://example.com/image.jpg',
                    'articleBody' => '<p>Test</p>',
                ]) . '</script>'
        );
});

it('can add multiple authors to Schema Markup: Article', function (): void {
    $articleSchema = new ArticleSchema($this->SEOData, [
        fn (ArticleSchema $article): ArticleSchema => $article
            ->addAuthor('Second author')
            ->markup(fn (Collection $markup): Collection => $markup->put('alternativeHeadline', 'My alternative headline')),
    ]);

    expect((string) $articleSchema->render())->toBe(
        '<script type="application/ld+json">' .
            json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => 'https://example.com/test',
                ],
                'datePublished' => now()->subDays(3)->toIso8601String(),
                'dateModified' => now()->toIso8601String(),
                'headline' => 'Test',
                'author' => [
                    [
                        '@type' => 'Person',
                        'name' => 'Ralph J. Smit',
                    ],
                    [
                        '@type' => 'Person',
                        'name' => 'Second author',
                    ],
                ],
                'description' => 'Description',
                'image' => 'https://example.com/image.jpg',
                'articleBody' => '<p>Test</p>',
                'alternativeHeadline' => 'My alternative headline',
            ]) . '</script>'
    );
});
