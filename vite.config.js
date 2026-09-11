import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Public site — a small core bundle plus one CSS file per route
                // (see App\View\Components\AppLayout). Keep in sync with
                // resources/sass/frontend/pages-entry/.
                'resources/sass/frontend/core.scss',
                'resources/sass/frontend/pages-entry/home.scss',
                'resources/sass/frontend/pages-entry/about.scss',
                'resources/sass/frontend/pages-entry/peoples-mayor.scss',
                'resources/sass/frontend/pages-entry/gallery.scss',
                'resources/sass/frontend/pages-entry/events.scss',
                'resources/sass/frontend/pages-entry/register.scss',
                'resources/sass/frontend/pages-entry/giving-back.scss',
                'resources/sass/frontend/pages-entry/contact.scss',
                'resources/sass/frontend/pages-entry/cookie-policy.scss',
                'resources/sass/frontend/pages-entry/sitemap.scss',
                'resources/sass/frontend/pages-entry/errors.scss',
                'resources/js/frontend.js',
                'resources/css/filament/admin/theme.css',
            ],
            refresh: true,
        }),
        // Tailwind only processes files that `@import 'tailwindcss'` — i.e. the
        // Filament admin theme. The public SCSS bundle is untouched.
        tailwindcss(),
    ],
});
