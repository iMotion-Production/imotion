import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { glob } from 'glob';

const cssFiles = glob.sync('resources/css/*.css');
const jsFiles = glob.sync('resources/js/blade/{layouts,pages}/*.js');

export default defineConfig({
    plugins: [
        laravel({
            input: [...cssFiles, ...jsFiles],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
