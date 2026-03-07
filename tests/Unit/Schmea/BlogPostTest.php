<?php

use Foodieneers\Laravel\SEO\Schema\BlogPost;
use Illuminate\Support\Facades\Date;

it('builds BlogPost schema payload without optional fields', function (): void {
    $publishedAt = Date::parse('2026-03-01T10:00:00+00:00');

    $schema = BlogPost::make(
        url: 'https://example.com/posts/hello-world',
        headline: 'Hello World',
        description: 'This is a hello world post.',
        datePublished: $publishedAt,
    )->toArray();

    expect($schema)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        '@id' => 'https://example.com/posts/hello-world/#blogpost',
        'url' => 'https://example.com/posts/hello-world',
        'headline' => 'Hello World',
        'description' => 'This is a hello world post.',
        'datePublished' => '2026-03-01T10:00:00+00:00',
    ]);
});

it('builds BlogPost schema payload with modified date, author and publisher', function (): void {
    $publishedAt = Date::parse('2026-03-01T10:00:00+00:00');
    $modifiedAt = Date::parse('2026-03-03T12:30:00+00:00');

    $schema = BlogPost::make(
        url: 'https://example.com/posts/hello-world',
        headline: 'Hello World',
        description: 'This is a hello world post.',
        datePublished: $publishedAt,
        dateModified: $modifiedAt,
        author: 'Marco Azzari',
        hasPublisher: true,
    )->toArray();

    expect($schema)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        '@id' => 'https://example.com/posts/hello-world/#blogpost',
        'dateModified' => '2026-03-03T12:30:00+00:00',
        'author' => [
            '@type' => 'Person',
            '@id' => 'https://www.marcoazzari.com/#person',
            'name' => 'Marco Azzari',
        ],
        'publisher' => [
            '@type' => 'Organization',
            '@id' => url('/#organization'),
        ],
    ]);
});
