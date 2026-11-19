<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;

define('FISHTANK_BLOCKS', [
	"text-and-image",
	"button",
	"button-group",
	"query-loop",
	// "fw-text",
	"logo-scroller",
	"faq-accordion",
	"card-scroller",
	"labeled-text-section",
	"expanding-service-cards",
]);

define('CORE_BLOCKS', [
	"block",
	"heading",
	"paragraph",
	"list",
	"list-item",
	"image",
	"button",
	"table",
]);

define('WYSIWYG', wp_json_encode([
	'core/heading',
	'core/paragraph',
	'core/list',
	'core/table',
	'fishtank/button-group',
	'fishtank/button',
]));

add_action('init', function () {

// Register Swiper shared styles
	wp_register_style('fishtank/swiper', Vite::asset('resources/styles/swiper.scss'), false, null);

// loop through the blocks and register them and their assets
	collect(FISHTANK_BLOCKS)->each(function ($block) {
		$block_json = get_template_directory() . '/resources/views/blocks/' . $block . '/block.json';
		register_block_type($block_json);

		if (locate_template($block_scss = "resources/views/blocks/{$block}/{$block}.scss", false, false)) {
			wp_register_style('fishtank/' . $block, Vite::asset($block_scss), false, null);
		}

		if (locate_template($block_js = "resources/views/blocks/{$block}/{$block}.js", false, false)) {
			wp_register_script('fishtank/' . $block, Vite::asset($block_js), null, null, true);
		}
	});

});

add_filter('allowed_block_types', function () {
	// prefix "fishtank/" to all blocks in the FISHTANK_BLOCKS array
	$fishtankBlocks = preg_filter('/^/', 'fishtank/', FISHTANK_BLOCKS);
	// prefix "core/" to all blocks in the CORE_BLOCKS array
	$coreBlocks = preg_filter('/^/', 'core/', CORE_BLOCKS);
	// merge the two arrays
	$allowed_blocks = array_merge($fishtankBlocks, $coreBlocks);
	// add any custom/plugin blocks here
	// $allowed_blocks[] = 'gravityforms/form';
	return $allowed_blocks;
});

add_action('wp_enqueue_scripts', function () {
	// Register the block styles
	// wp_dequeue_style('global-styles');

	// wp_dequeue_style('wp-block-button');
	if (function_exists('is_woocommerce_activated')) {
		wp_dequeue_style('wc-block-style'); // Remove WooCommerce block CSS
	}

}, 100);

function acf_block_render_callback($block, $content, $is_preview, $post_id)
{
	// set block ID to anchor if it existis
	$block['id'] = isset($block['anchor']) && !empty($block['anchor']) ? $block['anchor'] : $block['id'];
	// get the slug of the block
	$slug = str_replace('fishtank/', '', $block['name']);

	// put it all togather
	$block_data = [
		'block' => $block,
		'is_preview' => $is_preview,
		'post_id' => $post_id,
	];

	if (isset($block['allowed_blocks'])) {
		$block_data['allowed_blocks'] = wp_json_encode($block['allowed_blocks']);
	}
	if (isset($block['example']['attributes']['template'])) {
		$block_data['template'] = wp_json_encode($block['example']['attributes']['template']);
	}

	if (!empty($block['data']['is_preview'])) {
		echo \Roots\view("partials/block-preview", ['block' => $block])->render();
	} else if (\View::exists("blocks/$slug/$slug")) {
		echo \view("blocks/$slug/$slug", $block_data)->render();
	} else {
		echo 'No view file exists';
	}
}

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
	$style = Vite::asset('resources/styles/editor.scss');

	$settings['styles'][] = [
		'css' => "@import url('{$style}')",
	];

	return $settings;
});

/**
 * Add type="module" to Fishtank block scripts.
 */
add_filter('script_loader_tag', function ($tag, $handle, $src) {
	if (strpos($handle, 'fishtank') !== false) {
		return '<script type="module" src="' . esc_url($src) . '"></script>';
	}
	return $tag;

}, 10, 3);

/**
 * Customize the login screen styles.
 * @return void
 */
add_action('login_enqueue_scripts', function () {
	wp_enqueue_style('fishtank/login.css', 'https://wp-login.fishtank.dev/dist/css/fishtank-login.css', false, null);
}, 100);

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_filter('admin_head', function () {
	if (!get_current_screen()?->is_block_editor()) {
		return;
	}

	$dependencies = json_decode(Vite::content('editor.deps.json'));

	foreach ($dependencies as $dependency) {
		if (!wp_script_is($dependency)) {
			wp_enqueue_script($dependency);
		}
	}

	echo Vite::withEntryPoints([
		'resources/js/editor.js',
	])->toHtml();
});

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
	/**
	 * Disable full-site editing support.
	 *
	 * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
	 */
	remove_theme_support('block-templates');

	/**
	 * Register the navigation menus.
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
	 */
	register_nav_menus([
		'primary_navigation' => __('Primary Navigation', 'sage'),
	]);

	/**
	 * Disable the default block patterns.
	 *
	 * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
	 */
	remove_theme_support('core-block-patterns');

	/**
	 * Enable plugins to manage the document title.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
	 */
	add_theme_support('title-tag');

	/**
	 * Enable post thumbnail support.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	/**
	 * Enable responsive embed support.
	 *
	 * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
	 */
	add_theme_support('responsive-embeds');

	/**
	 * Enable HTML5 markup support.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
	 */
	add_theme_support('html5', [
		'caption',
		'comment-form',
		'comment-list',
		'gallery',
		'search-form',
		'script',
		'style',
	]);

	/**
	 * Enable selective refresh for widgets in customizer.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
	 */
	add_theme_support('customize-selective-refresh-widgets');
	/**
	 * Register the SVG blade directive.
	 *
	 * @return void
	 */
	Blade::directive('svg', function ($expression) {
		return "<?php
        // 1. Turn the raw string expression into an array of arguments
        \$params = [{$expression}];

        // 2. Grab the name and prepend your namespace
        \$name = 'SVG::' . \$params[0];

        // 3. Grab the data array (if it exists)
        \$data = \$params[1] ?? [];

        // 4. Check existence and render at RUNTIME
        echo view()->exists(\$name)
            ? view(\$name, \$data)->render()
            : '';
    ?>";
	});

}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
	$config = [
		'before_widget' => '<section class="widget %1$s %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	];

	register_sidebar([
		'name' => __('Primary', 'sage'),
		'id' => 'sidebar-primary',
	] + $config);

	register_sidebar([
		'name' => __('Footer', 'sage'),
		'id' => 'sidebar-footer',
	] + $config);
});

/**
 * When Fishtank theme is activated, activate ACF Pro if it is installed but inactive.
 *
 * @param string $new_name New theme name.
 * @param \WP_Theme $new_theme New theme object.
 * @param \WP_Theme $old_theme Old theme object.
 */
add_action('after_switch_theme', function ($new_name, $new_theme, $old_theme) {
	if ($new_theme->get_stylesheet() !== 'fishtank-theme') {
		return;
	}

	$acf_pro_plugin = 'advanced-custom-fields-pro/acf.php';

	if (!file_exists(WP_PLUGIN_DIR . '/' . $acf_pro_plugin)) {
		return;
	}

	if (!function_exists('activate_plugin')) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if (is_plugin_active($acf_pro_plugin)) {
		return;
	}

	activate_plugin($acf_pro_plugin);
}, 10, 3);
