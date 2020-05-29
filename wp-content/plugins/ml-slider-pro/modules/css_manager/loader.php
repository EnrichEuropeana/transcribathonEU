<?php if (!defined('ABSPATH')) die('No direct access.');
/** 
 * Load the necessary files
 */

add_filter('metaslider_add_css_module', 'metaslider_pro_css_module');
/**
 * Overrides the CSS module tease in free
 * 
 * @return string
 */
function metaslider_pro_css_module() {
	return '<button @click.prevent="notifyInfo(\'metaslider/open-css-managaer\', \'Opening CSS Editor...\')" title="' . __('Add CSS', 'ml-slider-pro') . '" class="ms-toolbar-button tipsy-tooltip-bottom-toolbar">
		<i>
			<font-awesome-icon transform="grow-2" icon="file-code" /></i>
		<span>' . __("Add CSS", "ml-slider-pro") . '</span>
	</button>';
}

add_filter('metaslider_css', 'metaslider_pro_css_module_inline_css', 999999, 3);
/**
 * Adds extra CSS to the end
 * 
 * @param string $css 		   - Other inline css styles
 * @param array  $settings	   - The slideshow settings
 * @param string $slideshow_id - The slideshow ID
 * 
 * @return string
 */
function metaslider_pro_css_module_inline_css($css, $settings, $slideshow_id) {
	if (apply_filters('metaslider_pro_filter_css_module_output', true)) {
		return $css .= str_replace('<', '&lt;', get_post_meta($slideshow_id, 'metaslider_extra_slideshow_css', true));
	}
	return $css .= get_post_meta($slideshow_id, 'metaslider_extra_slideshow_css', true);
}

// @codingStandardsIgnoreStart
// Not used but left in for future reference
// add_action('metaslider_add_external_components', 'metaslider_pro_css_manager_component');
// /**
//  * Overrides the CSS module tease in free
//  * 
//  * @return string
//  */
// function metaslider_pro_css_manager_component() {
// 	echo '<metaslider-pro-css-module></metaslider-pro-css-module>';
// }
// @codingStandardsIgnoreEnd

add_action('metaslider_slideshow_duplicated', 'metaslider_pro_duplicate_css_module', 10, 2);
/**
 * When slides are duplicated, replace the container id with the new one
 *
 * @param string|int $old_slideshow_id - The old slideshow id
 * @param string|int $new_slideshow_id - The new slideshow id
 * @return void
 */
function metaslider_pro_duplicate_css_module($old_slideshow_id, $new_slideshow_id) {
	$css = (string) get_post_meta(absint($new_slideshow_id), 'metaslider_extra_slideshow_css', true);
	$css = str_replace('#metaslider-id-' . $old_slideshow_id, '#metaslider-id-' . $new_slideshow_id, $css);
	update_post_meta(absint($new_slideshow_id), 'metaslider_extra_slideshow_css', $css);
}
