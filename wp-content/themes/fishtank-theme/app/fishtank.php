<?php

	namespace App;

	/*
	|--------------------------------------------------------------------------
	| Admin branding & UI
	|--------------------------------------------------------------------------
 */

	// Replace default "Thank you for creating with WordPress" with Fishtank credit.
	add_filter('admin_footer_text', function ($text) {
	return '<span>Fueled by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | Created by <a href="https://fishtankagency.com/" target="_blank">Fishtank Agency</a></span>';
	});

	// Hide the welcome/dashboard setup panel for new installs.
	remove_action('welcome_panel', 'wp_welcome_panel');

	/*
	|--------------------------------------------------------------------------
	| Frontend head cleanup (performance)
	|--------------------------------------------------------------------------
	| Removes unnecessary <head> output (feeds, generator, oEmbed, etc.) and
	| disables emoji scripts/styles to reduce payload and requests.
 */

	// Stop WordPress from outputting dns-prefetch hints in the frontend head.
	remove_action('wp_head', 'wp_resource_hints', 2);

	add_action('init', __NAMESPACE__ . '\\disable_header_stuff');

	/**
 * Strip unnecessary items from wp_head and disable emoji support.
 */
	function disable_header_stuff(): void
	{
	disable_emojis();
	// Feed links (RSS, etc.)
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'feed_links', 2);
	// RSD / WLW manifest (legacy editing tools)
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	// Relational post links (prev/next, etc.)
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
	// Generator meta (WP version)
	remove_action('wp_head', 'wp_generator');
	// REST API and oEmbed discovery links
	remove_action('wp_head', 'rest_output_link_wp_head');
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
	// Global styles SVG filters (block editor)
	remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
	remove_action('in_admin_header', 'wp_global_styles_render_svg_filters');
	}

	/**
 * Remove emoji scripts, styles, and conversion from frontend and admin.
 */
	function disable_emojis(): void
	{
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', __NAMESPACE__ . '\\disable_emojis_tinymce');
	add_filter('wp_resource_hints', __NAMESPACE__ . '\\disable_emojis_remove_dns_prefetch', 10, 2);
	}

	/** Remove the emoji plugin from TinyMCE so the toolbar doesn't offer it. */
	function disable_emojis_tinymce(array $plugins): array
	{
	return is_array($plugins) ? array_values(array_diff($plugins, ['wpemoji'])) : [];
	}

	/** Strip the emoji CDN URL from dns-prefetch hints (if hints are used elsewhere). */
	function disable_emojis_remove_dns_prefetch(array $urls, string $relation_type): array
	{
	if ($relation_type !== 'dns-prefetch') {
		return $urls;
	}
	$emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
	return array_values(array_diff($urls, [$emoji_svg_url]));
	}

	/*
	|--------------------------------------------------------------------------
	| Scripts (performance)
	|--------------------------------------------------------------------------
	| jQuery Migrate is only needed for very old themes/plugins; removing it
	| reduces frontend JS size (non-admin only).
 */

	add_action('wp_default_scripts', __NAMESPACE__ . '\\dequeue_jquery_migrate');

	/** Drop jquery-migrate from jQuery's dependencies on the frontend. */
	function dequeue_jquery_migrate($scripts): void
	{
	if (is_admin() || empty($scripts->registered['jquery'])) {
		return;
	}
	$scripts->registered['jquery']->deps = array_diff(
		$scripts->registered['jquery']->deps,
		['jquery-migrate']
	);
	}

	/*
	|--------------------------------------------------------------------------
	| Editor (TinyMCE)
	|--------------------------------------------------------------------------
	| Restrict block format dropdown so editors can't add H1 (reserved for page title).
 */

	add_filter('tiny_mce_before_init', __NAMESPACE__ . '\\tinymce_remove_h1');

	/** Set TinyMCE block formats to p, h2–h6, pre (no h1). */
	function tinymce_remove_h1(array $in): array
	{
	$in['block_formats'] = 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre';
	return $in;
	}

	/*
	|--------------------------------------------------------------------------
	| Comments (disabled site-wide)
	|--------------------------------------------------------------------------
	| Comments are fully disabled: no UI in admin, no form on frontend,
	| no comment links in admin bar. Edit Comments redirects to dashboard.
 */

	add_action('admin_init', __NAMESPACE__ . '\\comments_admin_redirect_and_cleanup');
	add_filter('comments_open', '__return_false', 20, 2);
	add_filter('pings_open', '__return_false', 20, 2);
	add_filter('comments_array', '__return_empty_array', 10, 2);
	add_action('admin_menu', __NAMESPACE__ . '\\comments_remove_menu');
	add_action('init', __NAMESPACE__ . '\\comments_remove_admin_bar');
	add_action('wp_before_admin_bar_render', __NAMESPACE__ . '\\comments_remove_admin_bar_icon');

	/**
 * Redirect Comments admin page to dashboard, remove comments metabox,
 * and disable comment/trackback support on all post types.
 */
	function comments_admin_redirect_and_cleanup(): void
	{
	global $pagenow;

	if ($pagenow === 'edit-comments.php') {
		wp_safe_redirect(admin_url());
		exit;
	}

	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

	foreach (get_post_types([], 'names') as $post_type) {
		if (post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
	}

	/** Remove the Comments item from the admin sidebar menu. */
	function comments_remove_menu(): void
	{
	remove_menu_page('edit-comments.php');
	}

	/** Remove the comments node from the admin bar menu (frontend + admin). */
	function comments_remove_admin_bar(): void
	{
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
	}

	/** Remove the comments bubble/icon from the admin bar. */
	function comments_remove_admin_bar_icon(): void
	{
	$GLOBALS['wp_admin_bar']->remove_menu('comments');
	}

	/*
	|--------------------------------------------------------------------------
	| Yoast SEO
	|--------------------------------------------------------------------------
 */

	// Don't output Yoast's HTML comments in the page source.
	add_filter('wpseo_debug_markers', '__return_false');
	// Move the Yoast SEO metabox below other metaboxes in the editor.
	add_filter('wpseo_metabox_prio', fn() => 'low');

	/*
	|--------------------------------------------------------------------------
	| User profile (non-admins)
	|--------------------------------------------------------------------------
	| On profile edit screens, hide fields that non-admins don't need
	| (e.g. username, URL, bio, application passwords).
 */

	add_action('admin_head', __NAMESPACE__ . '\\hide_profile_fields_for_non_admins');

	/** Output CSS to hide selected profile fields for non-administrators. */
	function hide_profile_fields_for_non_admins(): void
	{
	if (current_user_can('administrator')) {
		return;
	}
	echo '<style id="hide-unused-profile-fields">';
	echo 'form#your-profile > h2:first-of-type, form#your-profile > table:first-of-type, tr.user-url-wrap, tr.user-description-wrap, .application-passwords { display: none !important; }';
	echo '</style>';
	}

	/*
	|--------------------------------------------------------------------------
	| Gravity Forms (merge tags fix)
	|--------------------------------------------------------------------------
	| Ensures {save_link} and {save_token} appear in the merge tag dropdown
	| when configuring notifications for "Save & Continue" / "Save Email" events.
	| Only runs on the Gravity Forms form editor page.
 */

	add_action('admin_footer', __NAMESPACE__ . '\\gravity_forms_save_link_merge_tag_script');

	/** Output JS that adds Save & Continue merge tags to GF notification settings. */
	function gravity_forms_save_link_merge_tag_script(): void
	{
	if (!isset($_GET['page']) || $_GET['page'] !== 'gf_edit_forms') {
		return;
	}

	$save_link_label = esc_html__('Save & Continue Link', 'gravityforms');
	$save_token_label = esc_html__('Save & Continue Token', 'gravityforms');
	?>
<script type="text/javascript">
function MaybeAddSaveLinkMergeTag(mergeTags, elementId, hideAllFields, excludeFieldTypes, isPrepop, option) {
	var eventSelectEl = document.querySelector('select[name="_gform_setting_event"]');
	if (!eventSelectEl) {
		return mergeTags;
	}
	var event = eventSelectEl.value;
	if (event === 'form_saved' || event === 'form_save_email_requested') {
		mergeTags['other'].tags.push({
			tag: '{save_link}',
			label: <?php echo json_encode($save_link_label); ?>
		});
		mergeTags['other'].tags.push({
			tag: '{save_token}',
			label: <?php echo json_encode($save_token_label); ?>
		});
	}
	return mergeTags;
}
</script>
<?php
}