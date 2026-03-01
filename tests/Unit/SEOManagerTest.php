<?php

use Foodieneers\Laravel\SEO\SEOManager;

test('the SEOManager singleton works as expected', function (): void {
    $managerA = resolve(SEOManager::class);
    $managerB = resolve(SEOManager::class);
    $managerC = resolve(SEOManager::class);

    expect($managerA)
        ->toBe($managerB)
        ->toBe($managerC);
});
