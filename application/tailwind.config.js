import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['Source Code Pro', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                mercury: {
                    50: '#f0fdf6',
                    100: '#dcfce9',
                    200: '#bbf7d4',
                    300: '#86efb3',
                    400: '#4ade8a',
                    500: '#1ED980',
                    600: '#16C170',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                },
                surface: {
                    50: '#FFFFFF',
                    100: '#F7F8FA',
                    200: '#ECEFF4',
                    300: '#E2E8F0',
                    400: '#CBD5E1',
                },
                dark: {
                    DEFAULT: '#2B2B2B',
                    50: '#525252',
                    100: '#404040',
                    200: '#363636',
                    300: '#2B2B2B',
                    400: '#1F1F1F',
                    500: '#171717',
                },
            },
            spacing: {
                '18': '4.5rem',
                '22': '5.5rem',
            },
            borderRadius: {
                'xl': '1rem',
                '2xl': '1.5rem',
                '3xl': '2rem',
            },
            boxShadow: {
                'card': '0 2px 8px rgba(0, 0, 0, 0.06)',
                'card-hover': '0 8px 24px rgba(0, 0, 0, 0.1)',
                'input': '0 1px 2px rgba(0, 0, 0, 0.05)',
                'input-focus': '0 0 0 3px rgba(30, 217, 128, 0.15)',
            },
        },
    },

    plugins: [forms, typography],
};
