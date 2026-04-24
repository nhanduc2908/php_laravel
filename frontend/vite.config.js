import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import VueDevTools from 'vite-plugin-vue-devtools'
import compression from 'vite-plugin-compression'
import imagemin from 'vite-plugin-imagemin'

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), '')
    
    return {
        plugins: [
            vue(),
            VueDevTools(),
            AutoImport({
                imports: ['vue', 'vue-router', 'pinia'],
                dts: 'src/auto-imports.d.ts',
                eslintrc: {
                    enabled: true,
                },
            }),
            Components({
                dts: 'src/components.d.ts',
                dirs: ['src/components'],
            }),
            compression({
                algorithm: 'gzip',
                ext: '.gz',
                threshold: 10240,
            }),
            imagemin({
                gifsicle: { optimizationLevel: 7 },
                optipng: { optimizationLevel: 7 },
                jpegtran: { progressive: true },
                svgo: { plugins: [{ removeViewBox: false }] },
            }),
        ],
        
        resolve: {
            alias: {
                '@': resolve(__dirname, 'src'),
                '@components': resolve(__dirname, 'src/components'),
                '@views': resolve(__dirname, 'src/views'),
                '@api': resolve(__dirname, 'src/api'),
                '@stores': resolve(__dirname, 'src/stores'),
                '@utils': resolve(__dirname, 'src/utils'),
                '@assets': resolve(__dirname, 'src/assets'),
                '@layouts': resolve(__dirname, 'src/layouts'),
            },
        },
        
        server: {
            port: 5173,
            host: true,
            open: true,
            proxy: {
                '/api': {
                    target: env.VITE_API_URL || 'http://localhost:8000',
                    changeOrigin: true,
                    rewrite: (path) => path.replace(/^\/api/, '/api/v1'),
                },
                '/socket.io': {
                    target: env.VITE_WS_URL || 'ws://localhost:3000',
                    ws: true,
                },
            },
        },
        
        build: {
            outDir: 'dist',
            assetsDir: 'assets',
            sourcemap: mode === 'development',
            minify: 'terser',
            terserOptions: {
                compress: {
                    drop_console: mode === 'production',
                    drop_debugger: mode === 'production',
                },
            },
            rollupOptions: {
                output: {
                    manualChunks: {
                        vendor: ['vue', 'vue-router', 'pinia'],
                        ui: ['vue-chartjs', 'chart.js', 'echarts'],
                        utils: ['axios', 'moment', 'lodash'],
                    },
                },
            },
            chunkSizeWarningLimit: 1000,
        },
        
        css: {
            preprocessorOptions: {
                scss: {
                    additionalData: `@import "@/assets/scss/variables.scss";`,
                },
            },
        },
        
        optimizeDeps: {
            include: ['vue', 'vue-router', 'pinia', 'axios', 'socket.io-client'],
        },
        
        esbuild: {
            drop: mode === 'production' ? ['console', 'debugger'] : [],
        },
    }
})