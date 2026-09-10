import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/frontend/frontend.scss',
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
