<?php

use Roots\Acorn\Application;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
 */

if (!file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
	wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
 */

Application::configure()
	->withProviders([
		App\Providers\ThemeServiceProvider::class,
	])
	->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
 */

collect(['security-headers', 'setup', 'filters', 'fishtank', 'acf'])
	->each(function ($file) {
		if (!locate_template($file = "app/{$file}.php", true, true)) {
			wp_die(
				/* translators: %s is replaced with the relative file path */
				sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
			);
		}
	});

function placeholder($width, $height, $text = 'Placeholder')
{
	$placeholder = 'https://placehold.co/' . $width . 'x' . $height . '/png';
	$placeholder .= $text ? '?text=' . $text : '';
	return $placeholder;
}

/**
 * Stop REST API access for non-admin users.
 * @link https://developer.wordpress.org/reference/hooks/rest_authentication_errors/
 * @param WP_Error|WP_REST_Request $errors
 * @return WP_Error|WP_REST_Request
 */

add_filter('rest_authentication_errors', function ($errors) {
	if (is_wp_error($errors)) {
		return $errors;
	}

	if (!current_user_can('administrator')) {
		return new WP_Error('no_rest_api_sorry', 'REST API not allowed', array('status' => 401));
	}
});

function container_class($fields)
{
	if (isset($fields['disable_container']) && $fields['disable_container']) {
		return '';
	}
	return 'container--' . ($fields['container_size'] ?? 'md');
}
