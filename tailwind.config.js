import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],
    safelist: [
        "bg-sky-600",
        "hover:bg-sky-700",
        "bg-indigo-600",
        "hover:bg-indigo-700",
        "bg-emerald-600",
        "hover:bg-emerald-700",
        "bg-rose-600",
        "hover:bg-rose-700",
        "text-white",
        "text-rose-600",
        "border-rose-200",
        "bg-rose-50",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
        },
    },

    plugins: [forms],
};
