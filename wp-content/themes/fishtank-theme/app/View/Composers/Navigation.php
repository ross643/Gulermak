<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Navigation extends Composer
{
	/**
	 * List of views served by this composer.
	 *
	 * @var array
	 */
	protected static $views = [
		'partials.navigation.primary-navigation',
		'partials.navigation.*',
		'partials.navigation.primary-navigation--mobile',
	];

	/**
	 * Data to be passed to view before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		return [
			'menu' => $this->getMenu($this->data->get('menu_name')),
		];
	}

	public static function buildTree($elements, $parentId = 0)
	{
		/**
		 *
		 * DO NOT EDIT...
		 *
		 * All you need to know is that this creates a tree relatrional multidimentional array
		 * from the flat areray that wp_get_nav_menu_items() returns and adds and menu item
		 * acf fields to each item too.
		 */
		if (empty($elements)) {
			return array();
		}
		// create empty branch array
		$branch = array();
		// loop thorugh all nav items
		foreach ($elements as &$element) {
			// get any acf fields for the individual nav items and add to item object
			$element->acf_fields = get_fields($element);
			// if the item parent ID (defaults to 0) matches the iteration ID (self invoking)
			if ($element->menu_item_parent == $parentId) {
				// invoke self to add children
				$children = self::buildTree($elements, $element->ID);
				if ($children) {
					// add the children if they exits
					$element->children = $children;
				}
				// add to branch
				$branch[$element->ID] = $element;
				// uset so we dont iterate this element again
				unset($element);
			}
		}
		return $branch;
	}

	public function getMenu($menu_name)
	{
		//set up manu object
		$menu = (object) [];
		// gets menu object
		$menu->object = wp_get_nav_menu_object($menu_name);
		// gets acf fields of nav object
		$menu->acf_fields = get_fields($menu->object);
		// gets nav items of menu
		$nav_items = wp_get_nav_menu_items($menu->object);
		// builds multidimentional array of nav items
		$menu->nav_items = $this->buildTree($nav_items, 0);
		//returns menu as object
		/**
		 *
		 *  $menu->object []
		 *  $menu->acf_fields []
		 *  $menu->nav_items
		 */
		return $menu;
	}
}
