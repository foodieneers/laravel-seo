<?php

namespace Foodieneers\Laravel\SEO;

use Foodieneers\Laravel\SEO\Support\SEOData;
use Stringable;

class SEOService implements Stringable
{
    private ?SEOData $data = null;
    public function setData(SEOData $data): void
    {
        $this->data = $data;
    }

    public function hasData(): bool
    {
        return $this->data instanceof SEOData;
    }

    public function render(): string
    {
        if ($this->hasData()) {
            return resolve(TagManager::class)
                ->for($this->data)
                ->render();
        }

        $title = config('seo.site_name');

        return sprintf(
            '<title>%s</title><meta name="robots" content="noindex, nofollow, noarchive">',
            e($title)
        );
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
