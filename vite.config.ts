import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";
import tailwind from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        tailwind(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.ts"],
            refresh: true,
        }),
    ],
});
