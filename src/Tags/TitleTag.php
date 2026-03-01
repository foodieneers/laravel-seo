<?php

namespace Foodieneers\Laravel\SEO\Tags;

use Closure;
use Foodieneers\Laravel\SEO\Support\SEOData;
use Foodieneers\Laravel\SEO\Support\Tag;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;

class TitleTag extends Tag
{
    public string $tag = 'title';

    public function __construct(
        string $inner,
    ) {
        $this->inner = trim($inner);

        if ($this->isCurrentRouteInertiaRoute()) {
            $this->attributes['inertia'] = true;
        }
    }

    public static function initialize(?SEOData $SEOData): ?Tag
    {
        $title = $SEOData?->title;

        if (! $title) {
            return null;
        }

        return new static(
            inner: $title,
        );
    }

    protected function isCurrentRouteInertiaRoute(): bool
    {
        $currentRoute = Route::current();

        if (! $currentRoute) {
            return false;
        }

        return collect(Route::gatherRouteMiddleware($currentRoute))->contains(function (string | Closure $middleware): bool {
            if ($middleware instanceof Closure) {
                return false;
            }

            return is_subclass_of($middleware, Middleware::class);
        });
    }
}
