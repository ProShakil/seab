import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
             "colors": {
                "on-tertiary-container": "#4f3e00",
                "on-primary": "#ffffff",
                "tertiary-fixed-dim": "#e9c349",
                "inverse-primary": "#a7c8ff",
                "tertiary": "#735c00",
                "on-surface": "#191c1d",
                "secondary-container": "#c0d9ff",
                "inverse-surface": "#2e3132",
                "surface-container-highest": "#e1e3e4",
                "surface-container-low": "#f3f4f5",
                "on-tertiary-fixed": "#241a00",
                "tertiary-fixed": "#ffe088",
                "on-primary-fixed-variant": "#1f477b",
                "on-secondary-container": "#465f80",
                "background": "#f8f9fa",
                "primary-container": "#003366",
                "on-primary-fixed": "#001b3c",
                "surface-container-lowest": "#ffffff",
                "on-background": "#191c1d",
                "secondary-fixed": "#d3e3ff",
                "surface-container-high": "#e7e8e9",
                "primary-fixed-dim": "#a7c8ff",
                "on-error-container": "#93000a",
                "secondary": "#486081",
                "surface": "#f8f9fa",
                "on-secondary-fixed": "#001c39",
                "error-container": "#ffdad6",
                "primary": "#001e40",
                "on-primary-container": "#799dd6",
                "on-secondary": "#ffffff",
                "primary-fixed": "#d5e3ff",
                "outline": "#737780",
                "surface-container": "#edeeef",
                "surface-bright": "#f8f9fa",
                "error": "#ba1a1a",
                "surface-tint": "#3a5f94",
                "inverse-on-surface": "#f0f1f2",
                "on-error": "#ffffff",
                "on-tertiary-fixed-variant": "#574500",
                "on-tertiary": "#ffffff",
                "tertiary-container": "#cca830",
                "on-secondary-fixed-variant": "#2f4868",
                "secondary-fixed-dim": "#afc8ee",
                "surface-variant": "#e1e3e4",
                "on-surface-variant": "#43474f",
                "surface-dim": "#d9dadb",
                "outline-variant": "#c3c6d1"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "fontFamily": {
                    "headline": ["Manrope"],
                    "body": ["Inter"],
                    "label": ["Inter"]
            }
        },
    },

    plugins: [forms],
};
