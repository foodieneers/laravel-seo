<?php

namespace Foodieneers\Laravel\SEO\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Stringable;

class TagRender implements Renderable, Stringable
{
    public function __construct(
        public string $tag,
        public Collection $attributes,
        public null | string | HtmlString $inner = null,
    ) {
    }

    public function render(): string
    {
        $attributes = '';

        foreach ($this->attributes as $name => $value) {
            $attributes .= " {$name}";

            if (! is_bool($value)) {
                $attributes .= '="' . e($value) . '"';
            }
        }

        $html = "<{$this->tag}{$attributes}>";

        if ($this->inner) {
            $html .= e($this->inner) . "</{$this->tag}>";
        }

        return $html;
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
