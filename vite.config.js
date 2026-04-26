import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/main.css',
                'resources/js/main.js',
            ],
            refresh: true,
        }),
        VitePWA({
            registerType: 'autoUpdate',
            injectRegister: null,
            manifestFilename: 'manifest.webmanifest',
            workbox: {
                globPatterns: ['**/*.{css,ico,png,svg,woff2}'],
                // vite-plugin-pwa defaults to index.html + NavigationRoute (SPA). That breaks Laravel
                // server-rendered pages and sessions/CSRF (HTTP 419). Omit by overriding with undefined.
                navigateFallback: undefined,
            },
        }),
    ],
    resolve: {
        alias: {
            jquery: 'jquery/dist/jquery.js',
        },
    },
});
