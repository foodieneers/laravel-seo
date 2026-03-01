<?php

namespace Foodieneers\Laravel\SEO;

use const FILTER_VALIDATE_URL;

use Foodieneers\Laravel\SEO\Facades\SEOManager;
use Spatie\SchemaOrg\Schema;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\Support\SEOInputData;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Str;
use Stringable;

class TagManager implements Renderable, Stringable
{
    public ?SEOData $SEOData = null;

    public TagCollection $tags;

    public function __construct()
    {
        $this->tags = new TagCollection;
    }

    public function fillSEOData(SEOData | SEOInputData | null $source = null): SEOData
    {
        $SEOData = $this->resolveSEOData($source);

        $defaults = [
            'title' => config('seo.title.infer_title_from_url') ? $this->inferTitleFromUrl() : null,
            'description' => config('seo.description.fallback'),
            'image' => config('seo.image.fallback'),
            'site_name' => config('seo.site_name'),
            'author' => config('seo.author.fallback'),
            'twitter_username' => Str::of(config('seo.twitter.@username'))->start('@'),
            'favicon' => config('seo.favicon'),
        ];

        foreach ($defaults as $property => $defaultValue) {
            if ($SEOData->{$property} === null) {
                $SEOData->{$property} = $defaultValue;
            }
        }

        if ($SEOData->image && filter_var(str_replace(' ', '%20', $SEOData->image), FILTER_VALIDATE_URL) === false) {
            $SEOData->imageMeta();

            $SEOData->image = secure_url($SEOData->image);
        }

        if ($SEOData->favicon && filter_var(str_replace(' ', '%20', $SEOData->favicon), FILTER_VALIDATE_URL) === false) {
            $SEOData->favicon = secure_url($SEOData->favicon);
        }

        if (! $SEOData->url) {
            $SEOData->url = url()->current();
        }

        if ($SEOData->url === url('/') && ($homepageTitle = config('seo.title.homepage_title'))) {
            $SEOData->title = $homepageTitle;
        }

        return $SEOData->pipethrough(
            SEOManager::getSEODataTransformers()
        );
    }

    public function for(SEOData | SEOInputData $source): static
    {
        $this->SEOData = $this->fillSEOData($source);
        $this->tags = TagCollection::initialize($this->SEOData);

        return $this;
    }

    protected function resolveSEOData(SEOData | SEOInputData | null $source = null): SEOData
    {
        if ($source instanceof SEOData) {
            return $source;
        }

        if ($source instanceof SEOInputData) {
            return $this->mapInputToSEOData($source);
        }

        return new SEOData;
    }

    protected function mapInputToSEOData(SEOInputData $source): SEOData
    {
        $locale = $source->locale ?? app()->getLocale();
        $image = $source->image;
        $schema = null;
        $currentBreadcrumbName = $source->currentBreadcrumbName;

        if ($image !== null && filter_var(str_replace(' ', '%20', $image), FILTER_VALIDATE_URL) === false) {
            $image = url('/images/' . ltrim($image, '/'));
        }

        if ($source->schema !== []) {
            $schema = SchemaCollection::initialize();

            foreach ($source->schema as $schemaType) {
                if (is_string($schemaType) && $schemaType === 'BreadcrumbList') {
                    $currentBreadcrumbName ??= $this->inferTitleFromUrl();
                    $list = Schema::breadcrumbList();

                    foreach ($source->prependBreadcrumb as $name => $url) {
                        $list->itemListElement(Schema::listItem()
                            ->name($name)
                            ->item($url));
                    }
                    $list->itemListElement(Schema::listItem()
                        ->name($currentBreadcrumbName));

                    foreach ($source->appendBreadcrumb as $name => $url) {
                        $list->itemListElement(Schema::listItem()
                            ->name($name)
                            ->item($url));
                    }
                    $schema->add($list->toArray());

                    continue;
                }

                $schema->add($schemaType);
            }
        }

        return new SEOData(
            title: $source->title,
            description: $source->description,
            author: $source->author,
            image: $image,
            url: $source->url,
            published_time: $source->published_at,
            modified_time: $source->updated_at,
            articleBody: $source->articleBody,
            section: $source->section,
            tags: $source->tags,
            twitter_username: $source->twitter_username,
            schema: $schema,
            type: $source->type,
            site_name: $source->site_name,
            favicon: $source->favicon,
            locale: $locale,
            robots: $source->markAsNoindex ? 'noindex, nofollow' : $source->robots,
            canonical_url: $source->canonical_url,
            openGraphTitle: $source->openGraphTitle,
            alternates: $source->alternates,
        );
    }

    protected function inferTitleFromUrl(): string
    {
        return Str::of(url()->current())
            ->afterLast('/')
            ->headline();
    }

    public function render(): string
    {
        if (! $this->SEOData instanceof SEOData) {
            $this->SEOData = $this->fillSEOData();
            $this->tags = TagCollection::initialize($this->SEOData);
        }

        return $this->tags
            ->pipeThrough(SEOManager::getTagTransformers())
            ->reduce(fn (string $carry, Renderable $item): string => $carry .= Str::of($item->render())->trim() . PHP_EOL, '');
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
