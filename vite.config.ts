import path from 'path';
import { defineConfig } from 'laravel-vite';
import vue from '@vitejs/plugin-vue';
import Components from 'vite-plugin-components';
import ViteIcons, { ViteIconsResolver } from 'vite-plugin-icons';
import WindiCSS from 'vite-plugin-windicss';

export default defineConfig({
  resolve: {
    alias: {
      '@/': `${path.resolve(__dirname, 'resources/js')}/`,
    },
  },
}).withPlugins(
  vue(),
  Components({
    customComponentResolvers: ViteIconsResolver(),
  }),
  ViteIcons(),
  ...WindiCSS()
);
