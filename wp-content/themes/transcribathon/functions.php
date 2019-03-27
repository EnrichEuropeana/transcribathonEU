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


/* SHORTCODES */
// extract parameters for Document-View 
function _TCT_extract_params( $atts ) {   
    $current_site = get_blog_details(get_current_blog_id());
    $params = array();
    $params['doc'] = $_GET['d'];
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
