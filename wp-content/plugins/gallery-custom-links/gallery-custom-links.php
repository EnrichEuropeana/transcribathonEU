<?php
/*
Plugin Name: Gallery Custom Links
Plugin URI: https://meowapps.com
Description: Gallery Custom Links allows you to link images from galleries to a specified URL. Tested with WordPress Gallery, Gutenberg, the Meow Gallery and others.
Version: 1.2.6
Author: Jordy Meow
Author URI: https://meowapps.com
Text Domain: gallery-custom-links
Domain Path: /languages

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
*/

if ( class_exists( 'Meow_Gallery_Custom_Links' ) ) {
	function mfrh_admin_notices() {
		echo '<div class="error"><p>Thanks for installing Gallery Custom Links :) However, another version is still enabled. Please disable or uninstall it.</p></div>';
	}
	add_action( 'admin_notices', 'mfrh_admin_notices' );
	return;
}

global $mgcl_version;
$mgcl_version = '1.2.6';

include "mgcl_admin.php";
$mgcl_admin = new Meow_Gallery_Custom_Links_Admin( 'mgcl', __FILE__, 'gallery-custom-links' );

// Core
include "mgcl_core.php";
$mgcl_core = new Meow_Gallery_Custom_Links();

?>
