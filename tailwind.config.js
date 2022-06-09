const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: "1rem",
            },
        },
        extend: {
            fontFamily: {
                sans: defaultTheme.fontFamily.sans,
            },
            colors: {
                yellow: {
                    DEFAULT: "#f9e07d",
                },
                orange: {
                    DEFAULT: "#cc8b5c",
                },
                pink: {
                    DEFAULT: "#f5cbf7",
                },
                blue: {
                    100: "#f1f5f6",
                    200: "#cedfe3",
                    300: "#254c58",
                },
                green: {
                    100: "#c7d8d1",
                    200: "#50bfa5",
                    300: "#5b8280",
                    400: "#1e1e1e",
                },
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
