<?php
/* transcribathon functions and definitions */

// define constants
define('CHILD_TEMPLATE_DIR', dirname( get_bloginfo('stylesheet_url')) );
define( 'TCT_THEME_DIR_PATH', plugin_dir_path( __FILE__ ) );


// Custom Theme-Settings for Transcribathon
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_themesettings/tct-themesettings.php');

// Custom shortcodes
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/get_stories.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/story_page.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/item_page.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/item_page_test.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_profiletabs/transcriptions.php');

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


/* SHORTCODES */
// extract parameters for Document-View 
function _TCT_extract_params( $atts ) {   
    global $wp_query;
    $current_site = get_blog_details(get_current_blog_id());
    $params = array();
    $params['doc'] = $wp_query->query_vars;
    $params['page'] = $current_site;

    return "<pre>".print_r($params,true)."</pre>";
}
add_shortcode( 'get_doc_params', '_TCT_extract_params' );



/* Footer-Logos */
function _TCT_footer_logos( $atts ) {   
    $atts = shortcode_atts(
		array(
			'client' => '',
			'url' => '',
			'title' => '',
		), $atts, 'footer-logo' );

    if(trim($atts['url']) != ""){
        return '<a title="Opens in a new window: '.$atts['title'].'" href="' . $atts['url'].'" target="_blank" class="_tct_footerlogo '.$atts['client'].'">'.$atts['title'].'</a>';
    }else{
        return '<p class="_tct_footerlogo '.$atts['client'].'">'.$atts['title'].'</p>';
    }
}
add_shortcode( 'footer-logo', '_TCT_footer_logos' );

/* Add custom widgets */
function add_custom_widget_collection($folders){
    $folders[] = CHILD_TEMPLATE_DIR.'admin/inc/custom_widgets/';
    return $folders;
}
add_filter('siteorigin_widgets_widget_folders', 'add_custom_widget_collection');

require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-top-transcribers/tct-top-transcribers-widget.php'); // Adds the top-transcribers-widget

function add_scripts() {
    /* jQuery */
    wp_enqueue_script( 'jquery' );

    /* Bootstrap CSS */
    wp_enqueue_style( 'bootstrap', CHILD_TEMPLATE_DIR . '/css/bootstrap.min.css');
    /* Bootstrap JS */
    wp_enqueue_script('bootstrap', CHILD_TEMPLATE_DIR . '/js/bootstrap.min.js');

    /* splitjs CSS*/
    wp_enqueue_style( 'split', CHILD_TEMPLATE_DIR . '/css/splitjs.css');
    /* splitjs JS*/
    wp_enqueue_script( 'split', CHILD_TEMPLATE_DIR . '/js/split.js');

    /* resizable JS*/
    wp_enqueue_script( 'resizable', CHILD_TEMPLATE_DIR . '/js/jquery-resizable.js');

    /* slick CSS*/
    wp_enqueue_style( 'slick', CHILD_TEMPLATE_DIR . '/css/slick.css');
    /* slick JS*/
    wp_enqueue_script( 'slick', CHILD_TEMPLATE_DIR . '/js/slick.js');

    /* custom JS*/
    wp_enqueue_script( 'custom', CHILD_TEMPLATE_DIR . '/js/custom.js');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() .'/style.css', array('parent-style'));
}
add_action( 'wp_enqueue_scripts', 'add_scripts' );
