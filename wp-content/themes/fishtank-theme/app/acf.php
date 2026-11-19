<?php

namespace App;

if (class_exists('ACF')) {
	// Hide ACF if user role is not administrator
	if (is_user_logged_in()) {
		$current_user = wp_get_current_user();
		$roles = $current_user->roles;
		if (!in_array('administrator', $roles)) {
			add_filter('acf/settings/show_admin', '__return_false');
		}
	}

	if (function_exists('add_filter')) {

		/**
		 * Set local json save path
		 * @param  string $path unmodified local path for acf-json
		 * @return string       our modified local path for acf-json
		 */
		add_filter('acf/settings/save_json', function ($path) {
			$path = get_stylesheet_directory() . '/app/acf-json';
			if (!is_dir($path)) {
				mkdir($path);
			}
			return $path;
		});

		/**
		 * Set local json load path
		 * @param  string $path unmodified local path for acf-json
		 * @return string       our modified local path for acf-json
		 */
		add_filter('acf/settings/load_json', function ($paths) {
			$paths[] = get_stylesheet_directory() . '/app/acf-json';
			return $paths;
		});
	}

	/* SVG icons */
	add_filter('acf/load_field/name=svg_icon', function ($field) {
		// reset choices
		$field['choices'] = array();
		// get array of icons from svg icons folder...
		$svg_icons = scandir(__DIR__ . '/../resources/svg/icons/');
		//remove the .. and . from the array
		$svg_icons = array_diff($svg_icons, array('.', '..'));
		$field['choices']['none'] = 'None';
		// if we have an array...
		if (is_array($svg_icons)) {
			// loop through array
			foreach ($svg_icons as $icon) {
				// remove ".blade.php" from the name
				$icon_name = substr($icon, 0, -10);
				//User-friendly name
				$icon_label = str_replace('-', ' ', $icon_name);
				$icon_label = ucfirst($icon_label);

				$field['choices'][$icon_name] = $icon_label;
			}
		}
		// return the field
		return $field;
	});

	add_filter('acf/fields/wysiwyg/toolbars', function ($toolbars) {
		// Add a new toolbar called "Simple"
		// - this toolbar has only 1 row of buttons
		$toolbars['Simple'] = array();
		$toolbars['Simple'][1] = array('bold', 'italic', 'underline', 'link', 'removeformat');

		// Edit the "Full" toolbar and remove 'code' option
		// - delete from array code from http://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
		if (($key = array_search('code', $toolbars['Full'][2])) !== false) {
			unset($toolbars['Full'][2][$key]);
		}
		// return $toolbars - IMPORTANT!
		return $toolbars;
	});

}
