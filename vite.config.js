import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'; 

export default defineConfig(({ mode }) => {
    // Load .env untuk membaca VITE_NGROK_HOST
    const env = loadEnv(mode, process.cwd(), '');
    const hmrHost = env.VITE_NGROK_HOST || '127.0.0.1';

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(), // Plugin Tailwind v4
        ],
        server: {
            host: '0.0.0.0',
            // https: true, // HAPUS ATAU KOMENTAR jika tidak punya sertifikat SSL manual
            hmr: {
                host: hmrHost,
                // port: 5173, // Optional, default sudah 5173
            },
        }
    };
});