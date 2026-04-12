import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/themes/**/*.blade.php',
        './modules/**/Resources/views/**/*.blade.php',
        './Modules/**/Resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    primary: 'var(--writer-primary, #0F766E)',
                    secondary: 'var(--writer-secondary, #f0fdfa)',
                    accent: 'var(--brand-accent, #14B8A6)',
                    'accent-hover': 'var(--brand-accent-hover, #0d9488)',
                    'accent-soft': 'var(--brand-accent-soft, rgba(20, 184, 166, 0.1))',
                    muted: 'var(--writer-muted, #64748b)',
                },
                surface: {
                    tint: '#F0F9F8',
                    hover: '#E6F5F4',
                },
            },
            fontFamily: {
                sans: ['"Calibri"', '"Cairo"', ...defaultTheme.fontFamily.sans],
                serif: ['"Calibri"', '"Cairo"', ...defaultTheme.fontFamily.serif],
                calibri: ['"Calibri"', 'Arial', 'sans-serif'],
            },
            maxWidth: {
                prose: '70ch',
            },
            container: {
                center: true,
                padding: '1.5rem',
            },
        },
    },

    plugins: [forms, typography],
};
