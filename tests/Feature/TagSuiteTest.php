<?php

use Carbon\CarbonImmutable;
use Foodieneers\Laravel\SEO\Support\AlternateTag;
use Foodieneers\Laravel\SEO\Support\ImageMeta;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\Tags\OpenGraphTags;
use Foodieneers\Laravel\SEO\Tags\TwitterCard\Summary;
use Foodieneers\Laravel\SEO\Tags\TwitterCard\SummaryLargeImage;
use Foodieneers\Laravel\SEO\Tags\TwitterCardTags;

it('renders title tag without inertia attribute', function (): void {
    $output = renderSeo(new SEOData(
        title: 'My title',
        url: 'https://example.com/post',
    ));

    expect($output)
        ->toContain('<title>My title</title>')
        ->not->toContain('inertia');
});

it('renders description and author tags', function (): void {
    $output = renderSeo(new SEOData(
        description: 'Page description',
        author: 'Jane Doe',
        url: 'https://example.com/post',
    ));

    expect($output)
        ->toContain('<meta name="description" content="Page description">')
        ->toContain('<meta name="author" content="Jane Doe">');
});

it('renders canonical tag and can be disabled', function (): void {
    config()->set('seo.canonical_link', true);

    $withCanonical = renderSeo(new SEOData(
        url: 'https://example.com/post',
        canonical_url: 'https://example.com/canonical',
    ));

    expect($withCanonical)->toContain('<link rel="canonical" href="https://example.com/canonical">');

    config()->set('seo.canonical_link', false);

    $withoutCanonical = renderSeo(new SEOData(
        url: 'https://example.com/post',
    ));

    expect($withoutCanonical)->not->toContain('rel="canonical"');
});

it('renders robots tag using default or provided value', function (): void {
    config()->set('seo.robots.default', 'index, follow');
    config()->set('seo.robots.force_default', false);

    $defaultRobots = renderSeo(new SEOData(
        url: 'https://example.com/post',
    ));

    expect($defaultRobots)->toContain('<meta name="robots" content="index, follow">');

    $customRobots = renderSeo(new SEOData(
        url: 'https://example.com/post',
        robots: 'noindex, nofollow',
    ));

    expect($customRobots)->toContain('<meta name="robots" content="noindex, nofollow">');
});

it('renders sitemap tag from config', function (): void {
    config()->set('seo.sitemap', '/sitemap.xml');

    $output = renderSeo(new SEOData(
        url: 'https://example.com/post',
    ));

    expect($output)->toContain('<link rel="sitemap" title="Sitemap" href="/sitemap.xml" type="application/xml">');
});

it('renders image and favicon tags and resolves relative paths', function (): void {
    $output = renderSeo(new SEOData(
        image: '/images/social.jpg',
        url: 'https://example.com/post',
        favicon: '/favicon-test.ico',
    ));

    expect($output)
        ->toContain('<meta name="image" content="' . secure_url('/images/social.jpg') . '">')
        ->toContain('<link href="' . secure_url('/favicon-test.ico') . '" rel="shortcut icon">');
});

it('renders alternate tags when provided', function (): void {
    $output = renderSeo(new SEOData(
        url: 'https://example.com/en/post',
        alternates: [
            new AlternateTag('en', 'https://example.com/en/post'),
            new AlternateTag('fr', 'https://example.com/fr/post'),
        ],
    ));

    expect($output)
        ->toContain('<link rel="alternate" hreflang="en" href="https://example.com/en/post">')
        ->toContain('<link rel="alternate" hreflang="fr" href="https://example.com/fr/post">');
});

it('renders OpenGraph tags including article metadata', function (): void {
    $published = CarbonImmutable::parse('2026-01-01 10:00:00');
    $modified = CarbonImmutable::parse('2026-01-02 10:00:00');

    $imageMeta = new ImageMeta('https://cdn.example.com/cover.jpg');
    $imageMeta->width = 1200;
    $imageMeta->height = 630;

    $output = OpenGraphTags::initialize(new SEOData(
        title: 'Default title',
        description: 'OG description',
        image: 'https://cdn.example.com/cover.jpg',
        url: 'https://example.com/post',
        imageMeta: $imageMeta,
        published_time: $published,
        modified_time: $modified,
        section: 'News',
        tags: ['tag-one', 'tag-two'],
        type: 'article',
        site_name: 'Example',
        openGraphTitle: 'OG custom title',
    ))->render();

    expect($output)
        ->toContain('<meta property="og:title" content="OG custom title">')
        ->toContain('<meta property="og:description" content="OG description">')
        ->toContain('<meta property="og:image" content="https://cdn.example.com/cover.jpg">')
        ->toContain('<meta property="og:image:width" content="1200">')
        ->toContain('<meta property="og:image:height" content="630">')
        ->toContain('<meta property="og:url" content="https://example.com/post">')
        ->toContain('<meta property="og:site_name" content="Example">')
        ->toContain('<meta property="og:type" content="article">')
        ->toContain('<meta property="article:published_time" content="' . $published->toIso8601String() . '">')
        ->toContain('<meta property="article:modified_time" content="' . $modified->toIso8601String() . '">')
        ->toContain('<meta property="article:section" content="News">')
        ->toContain('<meta property="article:tag" content="tag-one">')
        ->toContain('<meta property="article:tag" content="tag-two">');
});

it('renders twitter card tags with summary card when ratio is near square', function (): void {
    $imageMeta = new ImageMeta('https://cdn.example.com/cover.jpg');
    $imageMeta->width = 1000;
    $imageMeta->height = 900;

    $output = TwitterCardTags::initialize(new SEOData(
        title: 'Twitter title',
        description: 'Twitter description',
        image: 'https://cdn.example.com/cover.jpg',
        imageMeta: $imageMeta,
        twitter_username: '@example',
    ))->render();

    expect($output)
        ->toContain('<meta name="twitter:card" content="summary">')
        ->toContain('<meta name="twitter:image" content="https://cdn.example.com/cover.jpg">')
        ->toContain('<meta name="twitter:title" content="Twitter title">')
        ->toContain('<meta name="twitter:description" content="Twitter description">')
        ->toContain('<meta name="twitter:site" content="@example">');
});

it('renders twitter card tags with large image card when ratio is wide', function (): void {
    $imageMeta = new ImageMeta('https://cdn.example.com/cover.jpg');
    $imageMeta->width = 2000;
    $imageMeta->height = 1000;

    $output = TwitterCardTags::initialize(new SEOData(
        title: 'Twitter title',
        image: 'https://cdn.example.com/cover.jpg',
        imageMeta: $imageMeta,
    ))->render();

    expect($output)->toContain('<meta name="twitter:card" content="summary_large_image">');
});

it('initializes summary twitter card only for supported dimensions', function (): void {
    $validMeta = new ImageMeta('https://cdn.example.com/cover.jpg');
    $validMeta->width = 500;
    $validMeta->height = 500;

    $valid = Summary::initialize(new SEOData(
        image: 'https://cdn.example.com/cover.jpg',
        imageMeta: $validMeta,
    ))->render();

    expect($valid)
        ->toContain('<meta name="twitter:card" content="summary">')
        ->toContain('<meta name="twitter:image:width" content="500">')
        ->toContain('<meta name="twitter:image:height" content="500">');

    $invalidMeta = new ImageMeta('https://cdn.example.com/cover.jpg');
    $invalidMeta->width = 100;
    $invalidMeta->height = 100;

    $invalid = Summary::initialize(new SEOData(
        image: 'https://cdn.example.com/cover.jpg',
        imageMeta: $invalidMeta,
    ));

    expect($invalid)->toHaveCount(0);
});

it('initializes large image twitter card only for supported dimensions', function (): void {
    $validMeta = new ImageMeta('https://cdn.example.com/cover.jpg');
    $validMeta->width = 1200;
    $validMeta->height = 630;

    $valid = SummaryLargeImage::initialize(new SEOData(
        image: 'https://cdn.example.com/cover.jpg',
        imageMeta: $validMeta,
    ))->render();

    expect($valid)
        ->toContain('<meta name="twitter:card" content="summary_large_image">')
        ->toContain('<meta name="twitter:image:width" content="1200">')
        ->toContain('<meta name="twitter:image:height" content="630">');

    $invalidMeta = new ImageMeta('https://cdn.example.com/cover.jpg');
    $invalidMeta->width = 200;
    $invalidMeta->height = 100;

    $invalid = SummaryLargeImage::initialize(new SEOData(
        image: 'https://cdn.example.com/cover.jpg',
        imageMeta: $invalidMeta,
    ));

    expect($invalid)->toHaveCount(0);
});
