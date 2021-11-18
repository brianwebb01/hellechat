const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // https://www.themes.dev/tailwindcss-colors/
                blue: { ...defaultTheme.colors.blue },
                violet: { ...defaultTheme.colors.violet },
                purple: { ...defaultTheme.colors.purple }
            },
            fontFamily: {
                //sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    variants: {
        opacity: ({ after }) => after(['disabled'])
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
