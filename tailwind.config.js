const defaultTheme = require('tailwindcss/defaultTheme')

let colors = require('tailwindcss/colors')

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                blue: { ...defaultTheme.colors.blue },
                violet: { ...defaultTheme.colors.violet },
                purple: { ...defaultTheme.colors.purple },
                green: colors.emerald,
            },
            fontFamily: { sans: ['Inter var', ...defaultTheme.fontFamily.sans] },
        },
    },
    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
}
