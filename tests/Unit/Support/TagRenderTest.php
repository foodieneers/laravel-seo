<?php

use Foodieneers\Laravel\SEO\Support\TagRender;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

it('renders tag with escaped attributes', function (): void {
    $render = new TagRender(
        tag: 'meta',
        attributes: new Collection([
            'name' => 'description',
            'content' => 'Tom & Jerry',
        ]),
    );

    expect($render->render())
        ->toBe('<meta name="description" content="Tom &amp; Jerry">');
});

it('renders boolean attributes without values', function (): void {
    $render = new TagRender(
        tag: 'script',
        attributes: new Collection([
            'defer' => true,
            'src' => '/app.js',
        ]),
    );

    expect((string) $render)
        ->toBe('<script defer src="/app.js">');
});

it('renders trusted HtmlString inner content and closing tag', function (): void {
    $render = new TagRender(
        tag: 'title',
        attributes: new Collection([]),
        inner: new HtmlString('A & B'),
    );

    expect($render->render())
        ->toBe('<title>A & B</title>');
});

it('renders escaped plain string inner content and closing tag', function (): void {
    $render = new TagRender(
        tag: 'title',
        attributes: new Collection([]),
        inner: 'A & B',
    );

    expect($render->render())
        ->toBe('<title>A &amp; B</title>');
});
