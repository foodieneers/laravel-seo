<?php

namespace Foodieneers\Laravel\SEO\Support;

use Carbon\CarbonInterface;

/**
 * User-facing SEO payload.
 *
 * This object is designed for ergonomic input and is transformed into SEOData
 * before tags are generated.
 */
final readonly class SEOInputData
{
    /**
     * @param  array<int, array<string, mixed>>  $schema
     * @param  null|array<array-key, AlternateTag>  $alternates
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $author = null,
        public ?string $image = null,
        public ?string $url = null,
        public ?CarbonInterface $published_at = null,
        public ?CarbonInterface $updated_at = null,
        public ?string $type = 'website',
        public ?string $site_name = null,
        public ?string $favicon = null,
        public ?string $locale = null,
        public ?string $robots = null,
        public ?string $canonical_url = null,
        public ?string $openGraphTitle = null,
        public array $schema = [],
        public ?array $alternates = null,
        public ?string $twitter_username = null,
        public ?string $articleBody = null,
        public ?string $section = null,
        public ?array $tags = null,
        public ?string $currentBreadcrumbName = null,
        public ?array $prependBreadcrumb = [],
        public ?array $appendBreadcrumb = [],
        public bool $markAsNoindex = false,
    ) {
    }
}
