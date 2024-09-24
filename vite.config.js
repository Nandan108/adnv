import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import dotenv from 'dotenv'

dotenv.config()

export default defineConfig({
    server: process.env.VITE_DEV_SERVER_URL
        ? {
            host: '0.0.0.0', // Listen on all addresses, including LAN
            port: 5173,
            hmr: {
                host: new URL(process.env.VITE_DEV_SERVER_URL).hostname,
            },
        }
        : undefined,
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                //base: null,
                // transformAssetUrls: {
                // The Vue plugin will re-write asset URLs, when referenced
                // in Single File Components, to point to the Laravel web
                // server. Setting this to `null` allows the Laravel plugin
                // to instead re-write asset URLs to point to the Vite
                // server instead.
                base: null,

                // The Vue plugin will parse absolute URLs and treat them
                // as absolute paths to files on disk. Setting this to
                // `false` will leave absolute URLs un-touched so they can
                // reference assets in the public directory as expected.
                includeAbsolute: false,
                // },
            }
        })
    ],
    resolve: {
        alias: {
            ziggy: path.resolve('vendor/tightenco/ziggy/dist/index.js'),
            // Couldn't get vscode to auto-import and recognize "@/" prefix,
            // So instead, let the compiler recognize auto-imported paths!
            Components: path.resolve('resources/js/Components'),
            Layouts: path.resolve('resources/js/Layouts'),
            Pages: path.resolve('resources/js/Pages'),
            models: path.resolve('resources/js/models'),
            services: path.resolve('resources/js/services'),
            stores: path.resolve('resources/js/stores'),
        }
    }
});
