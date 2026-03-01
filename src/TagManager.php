<?php

namespace Foodieneers\Laravel\SEO;

use Stringable;
use const FILTER_VALIDATE_URL;

use Foodieneers\Laravel\SEO\Facades\SEOManager;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Str;

class TagManager implements Renderable, Stringable
{
    public SEOData $SEOData;

    public TagCollection $tags;

    public function __construct()
    {
        $this->tags = TagCollection::initialize(
            $this->fillSEOData()
        );
    }

    public function fillSEOData(?SEOData $SEOData = null): SEOData
    {
        $SEOData ??= new SEOData;

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

    public function for(SEOData $source): static
    {
        $this->SEOData = $source;

        $this->tags = TagCollection::initialize(
            $this->fillSEOData($SEOData ?? new SEOData)
        );

        return $this;
    }

    protected function inferTitleFromUrl(): string
    {
        return Str::of(url()->current())
            ->afterLast('/')
            ->headline();
    }

    public function render(): string
    {
        return $this->tags
            ->pipeThrough(SEOManager::getTagTransformers())
            ->reduce(fn(string $carry, Renderable $item): string => $carry .= Str::of($item->render())->trim() . PHP_EOL, '');
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
