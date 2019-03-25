<?php
/* transcribathon functions and definitions */

// Konstanten definieren
define('CHILD_TEMPLATE_DIR', dirname( get_bloginfo('stylesheet_url')) );
define( 'TCT_THEME_DIR_PATH', plugin_dir_path( __FILE__ ) );


// Custom Theme-Settings for Transcribathon
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_themesettings/tct-themesettings.php');

// Embedd custom Javascripts and CSS
function embedd_custom_javascripts_and_css() {
    global $post;
     if (!is_admin() && $GLOBALS['pagenow'] != 'wp-login.php') {
        /* 
        wp_deregister_script( 'custom-js' );
        wp_register_script( 'custom-js', CHILD_TEMPLATE_DIR.'/js/custom.js', array('jquery'));
        wp_enqueue_script( 'custom-js' );
        */
         wp_register_style( 'custom-css', CHILD_TEMPLATE_DIR.'/css/custom.php');
         wp_enqueue_style( 'custom-css' ); 
     }
 }
 add_action('init', 'embedd_custom_javascripts_and_css');
