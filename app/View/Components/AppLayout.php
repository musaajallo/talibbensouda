<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * @param  string|null  $title  Page name, appended to the site name in <title>.
     * @param  string|null  $styles  Route bundle slug — resolves to
     *                               resources/sass/frontend/pages-entry/{slug}.scss,
     *                               loaded on top of core.scss for this page only.
     * @param  string|null  $description  Meta / OG / Twitter description for this page.
     * @param  string|null  $ogImage  Absolute URL for the social-share image.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $styles = null,
        public ?string $description = null,
        public ?string $ogImage = null,
    ) {}

    /** Vite entry for the page-specific stylesheet, or null. */
    public function styleEntry(): ?string
    {
        return $this->styles
            ? "resources/sass/frontend/pages-entry/{$this->styles}.scss"
            : null;
    }

    public function render(): View
    {
        return view('layouts.app');
    }
}
