<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
	return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Dynamically populate ACF select field with all available post types.
 * Used in the ACF options page to select a post type for the archive page.
 */
add_filter('acf/load_field/name=post_type', function ($field) {
	// Reset choices
	$field['choices'] = [];
	// Get all public post types
	$post_types = get_post_types(array('public' => true), 'names');
	// Loop through the post types and add them to the choices array
	foreach ($post_types as $post_type) {
		$field['choices'][$post_type] = ucfirst($post_type);
	}
	// Return the field with the choices
	return $field;
});

/**
 * Add "Archive" to the post states for the archive page.
 * Used in the WP admin to indicate that this is an archive page.
 */
add_filter("display_post_states", function ($post_states, $post) {
	// Get the list of post types from the ACF options page
	$cpt_types = get_field('post_types', 'option');
	// Filter the CPT list to just ones that are set to be an archive page
	$archive_ids = wp_list_pluck($cpt_types, 'page_id');
	// If the Page ID of the post is in the list of archive IDs, add "Archive" to the post states
	if (in_array($post->ID, $archive_ids)) {
		$post_states['cpt_archive'] = '<span style="">Archive</span>';
	}
	return $post_states;
}, 10, 2);

/**
 * Disable Gravity Forms default theme CSS.
 *
 * @return bool
 */
add_filter('gform_disable_form_theme_css', '__return_true');

/**
 * Disable REST API for non-logged in users.
 *
 * @return array
 */

add_filter('rest_endpoints', function ($endpoints) {
	if (isset($endpoints['/wp/v2/users']) && !is_user_logged_in()) {
		unset($endpoints['/wp/v2/users']);
	}
	if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)']) && !is_user_logged_in()) {
		unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
	}
	return $endpoints;
});