import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tsconfigPaths from 'vite-tsconfig-paths';

export default defineConfig(({ mode }) => ({
  base: '/',
  server: {
    hmr: mode !== 'test'
  },
  plugins: [
    laravel({
      input: 'resources/js/app.tsx',
      refresh: true
    }),
    react(),
    tsconfigPaths(),
  ]
}));
