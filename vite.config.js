import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,

        // Strong CORS fix for other devices
        cors: {
            origin: ['http://192.168.68.189:8000', 'http://localhost:8000'],
            methods: ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
            credentials: true,
        },

        hmr: {
            host: '192.168.68.189',
            protocol: 'http',
        },
    },
});
