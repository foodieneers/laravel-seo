<?php

namespace Foodieneers\Laravel\SEO\Support;

use Carbon\CarbonInterface;
use InvalidArgumentException;
use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\Graph;

class SEOData
{
    /**
     * @param  array<string, string>  $lang
     * @param  list<BaseType|Graph>  $schema
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $author = null,
        public ?string $image = null,
        public ?string $logo = null,
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
        public array $lang = [],
        public array $schema = [],
        public bool $markAsNoindex = false,
    ) {
        if ($this->locale === null) {
            $this->locale = app()->getLocale();
        }

        $this->assertValidSchema($this->schema);
    }

    /**
     * @param  array<int, mixed>  $schema
     */
    protected function assertValidSchema(array $schema): void
    {
        foreach ($schema as $item) {
            throw_if(! $item instanceof BaseType && ! $item instanceof Graph, InvalidArgumentException::class, 'Schema must be a BaseType or Graph.');
        }
    }

    public function imageMeta(): ?ImageMeta
    {
        if ($this->image) {
            return $this->imageMeta ??= new ImageMeta($this->image);
        }

        return null;
    }
}
