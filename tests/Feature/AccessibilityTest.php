<?php

use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

use function Pest\Laravel\get;

function withFieldErrors(array $fields): ViewErrorBag
{
    return (new ViewErrorBag)->put('default', new MessageBag(
        collect($fields)->mapWithKeys(fn (string $field) => [$field => ["The {$field} field is required."]])->all()
    ));
}

it('marks invalid contact form fields for assistive tech', function (): void {
    $html = view('contact', ['errors' => withFieldErrors(['name', 'email', 'subject', 'message'])])->render();

    foreach (['name', 'email', 'subject', 'message'] as $field) {
        expect($html)
            ->toContain('id="'.$field.'"')
            ->toContain('aria-invalid="true"')
            ->toContain('aria-describedby="'.$field.'-error"')
            ->toContain('id="'.$field.'-error"');
    }
});

it('marks invalid event registration fields for assistive tech', function (): void {
    $html = view('events.register', ['errors' => withFieldErrors(['name', 'email']), 'preselect' => null])->render();

    foreach (['name', 'email'] as $field) {
        expect($html)
            ->toContain('aria-invalid="true"')
            ->toContain('aria-describedby="'.$field.'-error"')
            ->toContain('role="alert"');
    }
});

it('exposes the gallery lightbox as a focus-trapped dialog with keyboard-operable photo tiles', function (): void {
    $html = get('/gallery')->assertOk()->getContent();

    expect($html)
        ->toContain('x-trap="lightboxOpen"')
        ->toContain('role="dialog"')
        ->toContain('aria-modal="true"')
        ->toContain('@keydown.space.prevent="open(idx)"')
        ->toContain(':aria-pressed="(active === cat).toString()"');
});
