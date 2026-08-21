import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.blade.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                // Konfigurasi Palette OkiVote (Light Mode Modern)
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8', // Main Primary
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
                accent: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    500: '#f59e0b',
                    600: '#d97706', // Main Gold Accent
                    700: '#b45309',
                },
                surface: {
                    bg: '#f8fafc',      // Background Halaman
                    card: '#ffffff',    // Background Kartu
                    border: '#e2e8f0',  // Garis Tepi
                }
            },
        },
    },
    plugins: [],
};