<?php

namespace Foodieneers\Laravel\SEO;

use const FILTER_VALIDATE_URL;

use Foodieneers\Laravel\SEO\Support\SEOData;
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

    public function for(SEOData $source): static
    {
        $this->SEOData = $this->normalize($source);
        $this->tags = TagCollection::initialize($this->SEOData);

        return $this;
    }

    protected function normalize(SEOData $source): SEOData
    {
        $url = $source->url ?: url()->current();
        $SEOData = clone $source;
        $title = $SEOData->title;

        if ($title === null && config('seo.title.infer_title_from_url')) {
            $title = $this->inferTitleFromUrl($url);
        }

        if ($url === url('/') && ($homepageTitle = config('seo.title.homepage_title'))) {
            $title = $homepageTitle;
        }

        $image = $SEOData->image;
        $favicon = $SEOData->favicon;

        if ($image !== null && filter_var(str_replace(' ', '%20', $image), FILTER_VALIDATE_URL) === false) {
            $image = secure_url($image);
        }

        if ($favicon !== null && filter_var(str_replace(' ', '%20', $favicon), FILTER_VALIDATE_URL) === false) {
            $favicon = secure_url($favicon);
        }

        $SEOData->title = $title;
        $SEOData->description ??= config('seo.description.fallback');
        $SEOData->author ??= config('seo.author.fallback');
        $SEOData->image = $image;
        $SEOData->url = $url;
        $SEOData->twitter_username ??= Str::of(config('seo.twitter.@username'))->start('@')->toString();
        $SEOData->site_name ??= config('seo.site_name');
        $SEOData->favicon = $favicon ?? config('seo.favicon');
        $SEOData->locale ??= app()->getLocale();
        $SEOData->robots = $SEOData->markAsNoindex ? 'noindex, nofollow' : $SEOData->robots;
        $SEOData->currentBreadcrumbName ??= $this->inferTitleFromUrl($url);
        if ($SEOData->image === null) {
            $SEOData->image = config('seo.image.fallback');
        }

        if ($SEOData->image && filter_var(str_replace(' ', '%20', $SEOData->image), FILTER_VALIDATE_URL) === false) {
            $SEOData->imageMeta();
            $SEOData->image = secure_url($SEOData->image);
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
            $this->for(new SEOData);
        }

        return $this->tags
            ->reduce(fn (string $carry, Renderable $item): string => $carry .= Str::of($item->render())->trim() . PHP_EOL, '');
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
