<?php

namespace Foodieneers\Laravel\SEO;

use const FILTER_VALIDATE_URL;

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

    public function for(SEOInputData $source): static
    {
        $this->SEOData = $this->buildFromInput($source);
        $this->tags = TagCollection::initialize($this->SEOData);

        return $this;
    }

    protected function buildFromInput(SEOInputData $source): SEOData
    {
        $url = $source->url ?: url()->current();
        $title = $source->title;

        if ($title === null && config('seo.title.infer_title_from_url')) {
            $title = $this->inferTitleFromUrl($url);
        }

        if ($url === url('/') && ($homepageTitle = config('seo.title.homepage_title'))) {
            $title = $homepageTitle;
        }

        $image = $source->image;
        $favicon = $source->favicon;

        if ($image !== null && filter_var(str_replace(' ', '%20', $image), FILTER_VALIDATE_URL) === false) {
            $image = secure_url($image);
        }

        if ($favicon !== null && filter_var(str_replace(' ', '%20', $favicon), FILTER_VALIDATE_URL) === false) {
            $favicon = secure_url($favicon);
        }

        $SEOData = new SEOData(
            title: $title,
            description: $source->description ?? config('seo.description.fallback'),
            author: $source->author ?? config('seo.author.fallback'),
            image: $image,
            url: $url,
            published_time: $source->published_at,
            modified_time: $source->updated_at,
            articleBody: $source->articleBody,
            section: $source->section,
            tags: $source->tags,
            twitter_username: $source->twitter_username
                ?? Str::of(config('seo.twitter.@username'))->start('@')->toString(),
            type: $source->type,
            site_name: $source->site_name ?? config('seo.site_name'),
            favicon: $favicon ?? config('seo.favicon'),
            locale: $source->locale ?? app()->getLocale(),
            robots: $source->markAsNoindex ? 'noindex, nofollow' : $source->robots,
            canonical_url: $source->canonical_url,
            openGraphTitle: $source->openGraphTitle,
            alternates: $source->alternates,
        );

        if ($SEOData->image === null) {
            $SEOData->image = config('seo.image.fallback');
        }

        if ($SEOData->image && filter_var(str_replace(' ', '%20', $SEOData->image), FILTER_VALIDATE_URL) === false) {
            $SEOData->imageMeta();
            $SEOData->image = secure_url($SEOData->image);
        }

        if ($SEOData->favicon && filter_var(str_replace(' ', '%20', $SEOData->favicon), FILTER_VALIDATE_URL) === false) {
            $SEOData->favicon = secure_url($SEOData->favicon);
        }

        return $SEOData;
    }

    protected function inferTitleFromUrl(string $url): string
    {
        return Str::of($url)
            ->afterLast('/')
            ->headline();
    }

    public function render(): string
    {
        if (! $this->SEOData instanceof SEOData) {
            $this->for(new SEOInputData);
        }

        return $this->tags
            ->reduce(fn (string $carry, Renderable $item): string => $carry .= Str::of($item->render())->trim() . PHP_EOL, '');
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
