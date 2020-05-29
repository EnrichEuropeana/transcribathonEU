<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link https://vedathemes.com
 * @since 1.0.0
 * @package Display_Post_Types
 *
 * @wordpress-plugin
 * Plugin Name: Display Post Types
 * Description: Filter, sort and display post, page or any post type.
 * Version: 1.4.0
 * Author: vedathemes
 * Author URI: https://vedathemes.com
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: display-post-types
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define plugin constants.
define( 'DISPLAY_POST_TYPES_DIR', plugin_dir_path( __FILE__ ) );

// Currently plugin version.
define( 'DISPLAY_POST_TYPES_VERSION', '1.4.0' );

// Load plugin textdomain.
add_action( 'plugins_loaded', 'display_post_types_plugins_loaded' );

// Load plugin's bridge functionality.
require DISPLAY_POST_TYPES_DIR . '/bridge/class-instance-counter.php';
require DISPLAY_POST_TYPES_DIR . '/bridge/functions.php';

// Load plugin's front-end functionality.
require DISPLAY_POST_TYPES_DIR . '/frontend/class-frontend.php';

// Load plugin's admin functionality.
require DISPLAY_POST_TYPES_DIR . '/backend/class-backend.php';

/**
 * Load plugin text domain.
 *
 * @since 1.0.0
 */
function display_post_types_plugins_loaded() {
	load_plugin_textdomain( 'display-post-types', false, DISPLAY_POST_TYPES_DIR . 'lang/' );
}
