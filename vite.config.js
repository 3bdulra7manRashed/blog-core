import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '127.0.0.1',
        port: 5173,
    },
    plugins: [
        laravel({
            input: [
                'resources/themes/classic/assets/css/app.css',
                'resources/themes/classic/assets/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
