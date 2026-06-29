import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/frontend/frontend.scss',
                'resources/sass/admin/admin.scss',
                'resources/js/frontend.js',
                'resources/js/admin.js',
            ],
            refresh: true,
        }),
    ],
});
