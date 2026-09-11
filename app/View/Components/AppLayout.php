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
     */
    public function __construct(
        public ?string $title = null,
        public ?string $styles = null,
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
