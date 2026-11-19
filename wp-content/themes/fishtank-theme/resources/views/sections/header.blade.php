@php
	/**
	 * This is the header section of the site.
	 *
	 * @package Fishtank
	 * https://developer.wordpress.org/reference/functions/wp_get_global_settings/
	 */
@endphp
@includeIf('partials.navigation.style-' . wp_get_global_settings(['custom', 'navigationStyle']), ['menu_name' => 'Primary Navigation'])
