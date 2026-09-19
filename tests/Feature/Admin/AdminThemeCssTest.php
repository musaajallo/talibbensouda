<?php

// Filament renders every modal as a `position: fixed` element INSIDE the table /
// section / widget that opened it. A transform (even the identity one) on any
// ancestor makes `position: fixed` resolve against that ancestor instead of the
// window — so a modal opened from a table was centred inside the table, and on a
// normal laptop screen ended up partly below the fold.
//
// The page-load "fade up" animation did exactly that by leaving its LAST keyframe
// (`transform: translateY(0)`) applied forever with `animation-fill-mode: both`.

it('does not leave a transform on page content once the entrance animation ends', function (): void {
    $css = file_get_contents(resource_path('css/filament/admin/theme.css'));

    preg_match_all('/animation\s*:[^;]*\bfi-fade-up\b[^;]*;/', $css, $matches);

    expect($matches[0])->not->toBeEmpty('the fade-up animation should be declared somewhere');

    foreach ($matches[0] as $declaration) {
        expect($declaration)
            ->not->toMatch('/\b(both|forwards)\b/', "\"{$declaration}\" keeps its last keyframe applied, trapping every modal inside it")
            ->and($declaration)->toContain('backwards');
    }
});
