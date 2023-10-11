import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

      // Configurações de construção
    build: {
        target: 'esnext', // ou 'es2015' para suporte a navegadores mais antigos
        outDir: 'dist', // Diretório de saída
    },

});
