import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./app/Livewire/**/*.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Geist", "Inter", ...defaultTheme.fontFamily.sans],
                mono: [
                    "Geist Mono",
                    "JetBrains Mono",
                    ...defaultTheme.fontFamily.mono,
                ],
            },
            colors: {
                signal: {
                    DEFAULT: "#4D61FC",
                    50: "#EEF0FE",
                    100: "#DCE1FD",
                    200: "#B8C3FB",
                    300: "#95A5F9",
                    400: "#7187F7",
                    500: "#4E69F5",
                    600: "#4D61FC",
                    700: "#3A4ED6",
                    800: "#2A3BAF",
                    900: "#1B2888",
                    950: "#0F1760",
                },
                slate: {
                    50: "#F8F9FC",
                    100: "#F0F2F8",
                    200: "#E2E5F0",
                    300: "#C8CCD8",
                    400: "#9DA3B4",
                    500: "#6B7190",
                    600: "#4A506E",
                    700: "#343A56",
                    800: "#1E2238",
                    900: "#131728",
                    950: "#0C0F1A",
                },
                success: {
                    DEFAULT: "#10B981",
                    dim: "#064E3B",
                },
                warning: {
                    DEFAULT: "#F59E0B",
                    dim: "#78350F",
                },
                error: {
                    DEFAULT: "#EF4444",
                    dim: "#7F1D1D",
                },
                text: {
                    primary: "var(--text-primary)",
                    secondary: "var(--text-secondary)",
                    tertiary: "var(--text-tertiary)",
                    disabled: "var(--text-disabled)",
                },
                bg: {
                    base: "var(--bg-base)",
                    subtle: "var(--bg-subtle)",
                    surface: "var(--surface-2)",
                    element: "var(--surface-3)",
                    border: "var(--border-base)",
                },
                surface: {
                    1: "var(--surface-1)",
                    2: "var(--surface-2)",
                    3: "var(--surface-3)",
                    4: "var(--surface-4)",
                },
                border: {
                    subtle: "var(--border-subtle)",
                    base: "var(--border-base)",
                    strong: "var(--border-strong)",
                },
            },
            boxShadow: {
                1: "0 1px 2px rgba(0,0,0,0.3), 0 0 0 1px var(--border-subtle)",
                2: "0 4px 16px rgba(0,0,0,0.4), 0 1px 4px rgba(0,0,0,0.3), 0 0 0 1px var(--border-base)",
                3: "0 16px 48px rgba(0,0,0,0.5), 0 4px 16px rgba(0,0,0,0.4), 0 0 0 1px var(--border-base)",
                4: "0 32px 80px rgba(0,0,0,0.6), 0 8px 32px rgba(0,0,0,0.5), 0 0 0 1px var(--border-strong)",
                "glow-signal":
                    "0 0 0 3px rgba(77, 97, 252, 0.25), 0 0 20px rgba(77, 97, 252, 0.15)",
                "glow-success":
                    "0 0 0 3px rgba(16, 185, 129, 0.25), 0 0 20px rgba(16, 185, 129, 0.10)",
                "glow-error":
                    "0 0 0 3px rgba(239, 68, 68, 0.25), 0 0 20px rgba(239, 68, 68, 0.10)",
                "neumorphic-light":
                    "10px 10px 20px rgba(0, 0, 0, 0.05), -10px -10px 20px rgba(255, 255, 255, 0.8)",
                "neumorphic-dark":
                    "10px 10px 20px rgba(0, 0, 0, 0.4), -10px -10px 20px rgba(255, 255, 255, 0.05)",
                "neumorphic-inset-light":
                    "inset 5px 5px 10px rgba(0, 0, 0, 0.05), inset -5px -5px 10px rgba(255, 255, 255, 0.8)",
                "neumorphic-inset-dark":
                    "inset 5px 5px 10px rgba(0, 0, 0, 0.4), inset -5px -5px 10px rgba(255, 255, 255, 0.05)",
                "neumorphic-knob":
                    "0px 8px 16px rgba(0,0,0,0.1), inset 0px 2px 4px rgba(255,255,255,0.8), inset 0px -2px 4px rgba(0,0,0,0.1)",
            },
            borderRadius: {
                sm: "4px",
                md: "8px",
                lg: "12px",
                xl: "16px",
                "2xl": "24px",
                full: "9999px",
            },
        },
    },

    plugins: [forms],
};
