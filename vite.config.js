import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/customer/products.js',
                'resources/js/customer/cart.js',
                'resources/js/customer/checkout.js',
                'resources/js/customer/product-show.js',
                'resources/js/customer/review-stars.js',
                'resources/js/customer/product-reviews-list.js',
                'resources/js/customer/order-show.js',
                'resources/js/admin/order-actions.js',
                'resources/js/admin/order-show.js',
                'resources/js/customer/reviews-page-htmx.js',
            ],
            refresh: [`resources/views/**/*`],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },
});
