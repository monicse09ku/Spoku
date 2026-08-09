import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                ink: {
                    DEFAULT: '#0B1220',
                    soft: '#1C2740',
                    mute: '#5B667A',
                },
                paper: {
                    DEFAULT: '#F2F4F7',
                    raised: '#FFFFFF',
                    line: '#D8DEE8',
                },
                signal: {
                    DEFAULT: '#C1121F',
                    deep: '#8B0C16',
                    soft: '#F8D7DA',
                },
                trust: {
                    DEFAULT: '#1A5F6F',
                    soft: '#D7EBEE',
                },
            },
            fontFamily: {
                display: ['"Libre Baskerville"', ...defaultTheme.fontFamily.serif],
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                soft: '0 1px 0 rgba(11, 18, 32, 0.04), 0 12px 32px rgba(11, 18, 32, 0.06)',
            },
            keyframes: {
                'rise-in': {
                    '0%': { opacity: '0', transform: 'translateY(14px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
            },
            animation: {
                'rise-in': 'rise-in 0.7s ease-out both',
                'fade-in': 'fade-in 0.5s ease-out both',
            },
        },
    },

    plugins: [forms],
};
