import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/frontend/frontend.scss',
                'resources/js/frontend.js',
            ],
            refresh: true,
        }),
    ],
});
