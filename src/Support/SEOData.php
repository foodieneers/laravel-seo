<?php

namespace Foodieneers\Laravel\SEO\Support;

use Carbon\CarbonInterface;

class SEOData
{
    /**
     * @param  null|array<array-key, AlternateTag>  $alternates
     * @param  array<int, string|array<string, mixed>>  $schema
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $author = null,
        public ?string $image = null,
        public ?string $url = null,
        public bool $enableTitleSuffix = true,
        public ?ImageMeta $imageMeta = null,
        public ?CarbonInterface $published_at = null,
        public ?CarbonInterface $modified_at = null,
        public ?string $articleBody = null,
        public ?string $section = null,
        public ?array $tags = null,
        public ?string $twitter_username = null,
        public ?string $type = 'website',
        public ?string $site_name = null,
        public ?string $favicon = null,
        public ?string $locale = null,
        public ?string $robots = null,
        public ?string $canonical_url = null,
        public ?string $openGraphTitle = null,
        public ?array $alternates = null,
        public array $schema = [],
        public ?string $currentBreadcrumbName = null,
        public ?array $breadcrumbs = [],
        public ?array $appendBreadcrumb = [],
        public ?string $area = null,
        public bool $markAsNoindex = false,
    ) {
        if ($this->locale === null) {
            $this->locale = app()->getLocale();
        }
    }

    public function imageMeta(): ?ImageMeta
    {
        if ($this->image) {
            return $this->imageMeta ??= new ImageMeta($this->image);
        }

        return null;
    }

    public function hasOrganization(): bool
    {
        return count($this->schema) > 0 && array_key_exists('Organization', $this->schema);
    }
}
