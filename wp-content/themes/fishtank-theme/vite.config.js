import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { wordpressPlugin } from '@roots/vite-plugin';
import fg from 'fast-glob';

export default defineConfig({
	envDir: '../../../',
	url: process.env.APP_URL,
	base: '/wp-content/themes/fishtank-theme/public/build/',
	server: {
		open: process.env.APP_URL,
		hmr: true,
	},
	build: {
		rollupOptions: {
			output: {
				// Chunk naming
				chunkFileNames: 'assets/[name]-[hash].js',
				entryFileNames: 'assets/[name]-[hash].js',
				assetFileNames: 'assets/[name]-[hash][extname]',

				// Manual Splitting
				manualChunks(id) {
					if (id.includes('node_modules/swiper')) return 'swiper';
					if (id.includes('node_modules/gsap')) return 'gsap';
				},
			},
		},
	},
	plugins: [
		laravel({
			input: [
				// 1. Static Entry Points (Keep these as strings)
				'resources/styles/critical.scss',
				'resources/styles/app.scss',
				'resources/styles/editor.scss',
				'resources/js/app.js',
				'resources/js/editor.js',
				'resources/styles/swiper.scss', // Shared Swiper Lib

				// 2. Wildcard Entry Points (Wrap these in fg.sync)
				...fg.sync([
					'resources/styles/navigation/*.scss',
					'resources/styles/footer/*.scss',
					'resources/js/navigation/*.js',
					'resources/views/blocks/**/*.{scss,js}', // Recursively finds all block assets
				]),
			],
			refresh: ['**/*.php', '**/*.js'],
		}),
		wordpressPlugin(),
	],
	resolve: {
		alias: {
			'@scripts': '/resources/js',
			'@styles': '/resources/styles',
			'@fonts': '/resources/fonts',
			'@images': '/resources/images',
		},
	},
});
