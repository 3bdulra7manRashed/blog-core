import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

export default defineConfig(({ mode }) => {
    // 1. Load env file based on `mode` in the current working directory.
    // Setting the third parameter to '' loads all env variables regardless of the `VITE_` prefix.
    const env = loadEnv(mode, process.cwd(), '');

    // 2. Retrieve active themes, defaulting to 'classic' if not explicitly defined
    const activeTheme = env.ACTIVE_THEME || 'classic';
    const adminActiveTheme = env.THEME_ADMIN_ACTIVE || 'classic';

    // 3. Define the expected paths dynamically based on active themes
    const themeAssets = [
        `resources/themes/${activeTheme}/assets/css/app.css`,
        `resources/themes/${activeTheme}/assets/js/app.js`,
    ];

    const adminAssets = [
        `resources/themes/admin/${adminActiveTheme}/assets/css/app.css`,
        `resources/themes/admin/${adminActiveTheme}/assets/js/app.js`,
    ];

    // Filter paths to only include existing files (prevents Vite compilation failure)
    const inputAssets = [...themeAssets, ...adminAssets].filter((assetPath) => {
        return fs.existsSync(path.resolve(process.cwd(), assetPath));
    });

    return {
        server: {
            host: '127.0.0.1',
            port: 5173,
        },
        plugins: [
            laravel({
                input: inputAssets,
                refresh: true,
            }),
        ],
    };
});
