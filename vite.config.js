import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["frontend/css/app.css", "frontend/js/app.js"],
            refresh: ["frontend/views/**", "routes/**"],
        }),
    ],
});
