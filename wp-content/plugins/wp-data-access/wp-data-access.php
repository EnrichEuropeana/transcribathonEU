<?php
/**
 * Plugin Name:       WP Data Access
 * Plugin URI:        https://wpdataaccess.com/
 * Description:       Local and remote data administration, publication and app development tool available directly from the WordPress dashboard.
 * Version:           3.1.1
 * Author:            Peter Schulz
 * Author URI:        https://www.linkedin.com/in/peterschulznl/
 * Text Domain:       wp-data-access
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 *
 * @package plugin
 * @author  Peter Schulz
 * @since   1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

const SETTINGS_URL = 'options-general.php?page=wpdataaccess';
const WEBSITE_URL  = 'https://wpdataaccess.com/';
function wpda_row_meta( $links, $file ) {
	if ( strpos( $file, plugin_basename(__FILE__) ) !== false ) {
		// Add settings link
		$settings_url  = esc_url( get_admin_url() . SETTINGS_URL );
		$settings_link = "<a href='$settings_url'>Settings</a>";
		array_push( $links, $settings_link );

		// Add website link
		$website_url  = esc_url( WEBSITE_URL );
		$website_link = "<a href='$website_url'>Website</a>";
		array_push( $links, $website_link );
	}

	return $links;
}
add_filter( 'plugin_row_meta', 'wpda_row_meta', 10, 2 );

// Load WPDataAccess namespace.
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Activate plugin
 *
 * @author  Peter Schulz
 * @since   1.0.0
 */
function activate_wp_data_access() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-data-access-switch.php';
	WP_Data_Access_Switch::activate();
}
register_activation_hook( __FILE__, 'activate_wp_data_access' );

/**
 * Deactivate plugin
 *
 * @author  Peter Schulz
 * @since   1.0.0
 */
function deactivate_wp_data_access() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-data-access-switch.php';
	WP_Data_Access_Switch::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_wp_data_access' );

/**
 * Check if database needs to be updated
 *
 * @author  Peter Schulz
 * @since   1.5.2
 */
function wpda_update_db_check() {
	if ( WPDataAccess\WPDA::OPTION_WPDA_VERSION[1] !== get_option( WPDataAccess\WPDA::OPTION_WPDA_VERSION[0] ) ) {
		activate_wp_data_access();
	}
}
add_action( 'plugins_loaded', 'wpda_update_db_check' );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-data-access.php';
/**
 * Start plugin
 *
 * @author  Peter Schulz
 * @since   1.0.0
 */
function run_wp_data_access() {
	$wpdataaccess = new WP_Data_Access();
	$wpdataaccess->run();
}
run_wp_data_access();
