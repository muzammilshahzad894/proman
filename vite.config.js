import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@public/': `${path.resolve(__dirname, './public')}/`,
            '@/': `${path.resolve(__dirname, './resources/js')}/`,
        }
    },
    build: {
        sourcemap: false, // Disable source maps in production build
    },
    server: {
        sourcemap: false, // Disable source maps in dev mode
    },
});
