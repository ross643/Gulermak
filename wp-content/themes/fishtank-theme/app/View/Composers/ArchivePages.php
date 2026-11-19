<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class ArchivePages extends Composer
{
	/**
	 * List of views served by this composer.
	 *
	 * @var array
	 */
	protected static $views = [
		'archive',
	];

	/**
	 * Data to be passed to view before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		return [
			'blocks' => $this->getArchiveBlocks(),
		];
	}

	/**
	 * Returns the blocks on a specific page.
	 *
	 * @return string
	 */
	public function getArchiveBlocks()
	{
		// Get ACF select field of all available post types (dynamically populated)
		$cpt_types = get_field('post_types', 'option');
		if (!$cpt_types) {
			return [];
		}
		// Get the current post type from the query variable of the archive page you are on
		$current_post_type = get_query_var('post_type');
		// Set page id to false for the default case
		$page_id = false;
		// Loop through the post types and find the one that matches the current post type
		foreach ($cpt_types as $cpt_type) {
			if ($cpt_type['post_type'] === $current_post_type) {
				// Get the page id from the post type if we have a match
				$page_id = $cpt_type['page_id'];
				// Break the loop as we have found the page id
				break;
			}
		}
		// If we have a page id, get the blocks from the post content
		if ($page_id) {
			$post_content = get_post($page_id);
			$blocks = parse_blocks($post_content->post_content);
			return $blocks;
		} else {
			// If we don't have a page id, return an empty array
			return [];

		}
	}
}
