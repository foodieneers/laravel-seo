<?php

namespace Foodieneers\Laravel\SEO;

use Foodieneers\Laravel\SEO\Support\SEOInputData;
use Stringable;

class SEOService implements Stringable
{
    private ?SEOInputData $data = null;

    public function setData(SEOInputData $data): void
    {
        $this->data = $data;
    }

    public function hasData(): bool
    {
        return $this->data !== null;
    }

    public function reset(): void
    {
        $this->data = null;
    }

    public function render(): string
    {
        if ($this->hasData()) {
            return resolve(TagManager::class)
                ->for($this->data)
                ->render();
        }

        $title =  config('seo.site_name');

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
