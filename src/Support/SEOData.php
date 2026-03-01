<?php

namespace Foodieneers\Laravel\SEO\Support;

use Carbon\CarbonInterface;
use Foodieneers\Laravel\SEO\SchemaCollection;
use RalphJSmit\Helpers\Laravel\Pipe\Pipeable;

class SEOData
{
    use Pipeable;

    /**
     * @param  null|array<array-key, AlternateTag>  $alternates
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $author = null,
        public ?string $image = null,
        public ?string $url = null,
        public bool $enableTitleSuffix = true,
        public ?ImageMeta $imageMeta = null,
        public ?CarbonInterface $published_time = null,
        public ?CarbonInterface $modified_time = null,
        public ?string $articleBody = null,
        public ?string $section = null,
        public ?array $tags = null,
        public ?string $twitter_username = null,
        public ?SchemaCollection $schema = null,
        public ?string $type = 'website',
        public ?string $site_name = null,
        public ?string $favicon = null,
        public ?string $locale = null,
        public ?string $robots = null,
        public ?string $canonical_url = null,
        public ?string $openGraphTitle = null,
        public ?array $alternates = null,
        public ?string $currentBreadcrumbName = null,
        public ?array $prependBreadcrumb = [],
        public ?array $appendBreadcrumb = [],
        public ?CarbonInterface $published_at = null,
        public ?CarbonInterface $updated_at = null,
    ) {
        if ($this->locale === null) {
            $this->locale = app()->getLocale();
        }

        // Backwards compatible aliases for user-facing properties.
        $this->published_time ??= $this->published_at;
        $this->modified_time ??= $this->updated_at;
    }

    public function imageMeta(): ?ImageMeta
    {
        if ($this->image) {
            return $this->imageMeta ??= new ImageMeta($this->image);
        }

        return null;
    }

    public function markAsNoindex(): static
    {
        $this->robots = 'noindex, nofollow';

        return $this;
    }
}
