import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        rollupOptions: {
            input: 'resources/js/script.js',
            output: {
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`
            },
        },
        outDir: 'public',
        assetsDir: '',
        emptyOutDir: false,
        manifest: false,
        minify: false,
    },
});
