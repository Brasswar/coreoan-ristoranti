import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#f97316', // Main orange
                    600: '#ea580c',
                    700: '#c2410c',
                    800: '#9a3412',
                    900: '#7c2d12',
                },
            },
        },
    },

    plugins: [
        forms,
        require('daisyui'),
    ],

    daisyui: {
        themes: [
            {
                restaurant: {
                    "primary": "#f97316",        // Orange
                    "secondary": "#64748b",      // Slate
                    "accent": "#f59e0b",         // Amber
                    "neutral": "#1f2937",        // Gray-800
                    "base-100": "#ffffff",       // White
                    "base-200": "#f8fafc",       // Slate-50
                    "base-300": "#e2e8f0",       // Slate-200
                    "info": "#3b82f6",           // Blue
                    "success": "#10b981",        // Green
                    "warning": "#f59e0b",        // Amber
                    "error": "#ef4444",          // Red
                },
            },
        ],
        darkTheme: false,
        base: true,
        styled: true,
        utils: true,
        prefix: "",
        logs: false,
    },
};
