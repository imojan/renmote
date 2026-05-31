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
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                poppins: ['Poppins', 'sans-serif'],
                roboto: ['Roboto', 'sans-serif'],
                montserrat: ['Montserrat', 'sans-serif'],
                jakarta: ['"Plus Jakarta Sans"', 'sans-serif'],
            },
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#1a73e8',
                    600: '#0d5bbd',
                    700: '#0b4a9a',
                    800: '#0a3d7f',
                    900: '#082f61',
                },
                /**
                 * Renmote brand palette.
                 * Mirrors CSS variables defined in resources/css/front-layout.css (:root)
                 * so utility classes and legacy `var(--rn-*)` references stay in sync.
                 */
                rn: {
                    blue: {
                        DEFAULT: '#0058BC',
                        dark: '#004299',
                    },
                    primary: {
                        DEFAULT: '#1565C0',
                        dark: '#0D47A1',
                    },
                    accent: '#FF6B00',
                    green: {
                        DEFAULT: '#22C55E',
                        dark: '#16a34a',
                    },
                    text: '#1a1a2e',
                    muted: '#6b7280',
                    border: '#e5e7eb',
                    bg: '#f8fafc',
                },
            },
        },
    },

    plugins: [forms],
};
