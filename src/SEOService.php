<?php

namespace Foodieneers\Laravel\SEO;

use Foodieneers\Laravel\SEO\Support\SEOInputData;
use LogicException;
use Stringable;

class SEOService implements Stringable
{
    private ?SEOInputData $data = null;

    private bool $hasRendered = false;

    public function setData(SEOInputData $data): void
    {
        $this->data = $data;
    }

    public function hasData(): bool
    {
        return $this->data instanceof SEOInputData;
    }

    public function render(): string
    {
        throw_if($this->hasRendered, LogicException::class, 'SEOService can only be rendered once per request.');

        $this->hasRendered = true;

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
