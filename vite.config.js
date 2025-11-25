import { defineConfig, loadEnv } from 'vite'; // Wajib import loadEnv
import laravel from 'laravel-vite-plugin';
import mkcert from 'vite-plugin-mkcert'; // Untuk HTTPS lokal (jika Ngrok mati)
import tailwindcss from '@tailwindcss/vite'; 

export default defineConfig(({ mode }) => {
    // 1. Muat environment variables dari .env (untuk membaca VITE_NGROK_HOST)
    const env = loadEnv(mode, process.cwd(), '');
    
    // Tentukan HMR host: Gunakan Ngrok host jika ada, jika tidak, gunakan localhost
    // Karena Anda ingin Artisan di 127.0.0.1, kita gunakan 127.0.0.1 sebagai fallback
    const hmrHost = env.VITE_NGROK_HOST || '127.0.0.1';

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(), // Plugin TailwindCSS
            mkcert(), // Plugin HTTPS
        ],
        server: {
            // Kita tetap mengizinkan host: '0.0.0.0' agar Vite bisa diakses dari Ngrok
            host: '0.0.0.0', 
            https: true, // Wajib aktif untuk Web Bluetooth
            hmr: {
                // 2. Gunakan Ngrok Host (jika ada) atau fallback ke 127.0.0.1
                host: hmrHost, 
                port: 5173, 
                protocol: 'wss', // Wajib untuk HTTPS (Ngrok)
            },
        }
    };
});