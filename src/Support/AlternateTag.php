<?php

namespace Foodieneers\Laravel\SEO\Support;

class AlternateTag extends LinkTag
{
    public function __construct(
        string $hreflang,
        string $href,
    ) {
        parent::__construct('alternate', $href);

        $this->attributes['hreflang'] = $hreflang;
    }
}
