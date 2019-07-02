<?php
/* transcribathon functions and definitions */

add_filter( 'solr_scheme', function(){ return 'http'; });
define( 'SOLR_PATH', '/home/enrich/solr-7.7.1/bin/solr' );

// define constants
define('CHILD_TEMPLATE_DIR', dirname( get_bloginfo('stylesheet_url')) );
define( 'TCT_THEME_DIR_PATH', plugin_dir_path( __FILE__ ) );


// Custom Theme-Settings for Transcribathon
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_themesettings/tct-themesettings.php');

// Custom shortcodes
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/story_page.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/item_page.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/item_page_test.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/item_page_test_ad.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/item_page_test_iiif.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/solr_test.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_profiletabs/transcriptions.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_profiletabs/teams_runs.php');

// Custom posts
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_posts/tct-news.php'); // Adds custom post-type: news


// Image settings
add_image_size( 'news-image', 300, 200, true );


// Embedd custom Javascripts and CSS
function embedd_custom_javascripts_and_css() {
    global $post;
     if (!is_admin() && $GLOBALS['pagenow'] != 'wp-login.php') {
        /* jQuery */
        wp_enqueue_script( 'jquery' );

        /* Bootstrap CSS */
        wp_enqueue_style( 'bootstrap', CHILD_TEMPLATE_DIR . '/css/bootstrap.min.css');
        /* Bootstrap JS */
        wp_enqueue_script('bootstrap', CHILD_TEMPLATE_DIR . '/js/bootstrap.min.js');

        /* chart JS */
        wp_enqueue_style( 'chart', CHILD_TEMPLATE_DIR . '/css/chart.min.css');
        /* chart JS */
        wp_enqueue_script( 'chart', CHILD_TEMPLATE_DIR . '/js/chart.min.js');

        /* slick CSS*/
        wp_enqueue_style( 'slick', CHILD_TEMPLATE_DIR . '/css/slick.css');
        /* slick JS*/
        wp_enqueue_script( 'slick', CHILD_TEMPLATE_DIR . '/js/slick.min.js');

        /* progress chart CSS*/
        wp_enqueue_style( 'chartist', CHILD_TEMPLATE_DIR . '/css/chartist.min.css');
        /* progress chart JS*/
        wp_enqueue_script( 'chartist', CHILD_TEMPLATE_DIR . '/js/chartist.min.js');

        /* jQuery UI CSS*/
        wp_enqueue_style( 'jQuery-UI', CHILD_TEMPLATE_DIR . '/css/jquery-ui.min.css');
        /* jQuery UI JS*/
        wp_register_script( 'jQuery-UI', CHILD_TEMPLATE_DIR . '/js/jquery-ui.min.js');
        /* jQuery UI JS*/
        wp_enqueue_script( 'jQuery-UI' );

        /* Openseadragon */
        wp_enqueue_script( 'osd', CHILD_TEMPLATE_DIR . '/js/openseadragon.js');

      	/* TinyMCE */
      	wp_enqueue_script( 'tinymce', CHILD_TEMPLATE_DIR . '/js/tinymce/js/tinymce/tinymce.js');

        /* iiif viewer */
        wp_enqueue_script( 'viewer', CHILD_TEMPLATE_DIR . '/js/tct-image-viewer.js');
        wp_enqueue_style( 'viewer', CHILD_TEMPLATE_DIR . '/css/viewer.css');

        /* resizable JS*/
        wp_register_script( 'resizable', CHILD_TEMPLATE_DIR . '/js/jquery-resizable.js', array( 'jQuery-UI' ) );
        wp_enqueue_script( 'resizable' );

        /* Font Awesome CSS */
        wp_enqueue_style( 'font-awesome', CHILD_TEMPLATE_DIR . '/css/all.min.css');

        /* diff-match-patch (Transcription text comparison) JS*/
        wp_enqueue_script( 'diff-match-patch', CHILD_TEMPLATE_DIR . '/js/diff-match-patch.js');

        /* custom JS and CSS*/
        wp_enqueue_script( 'custom', CHILD_TEMPLATE_DIR . '/js/custom.js');
        wp_enqueue_style('child-style', get_stylesheet_directory_uri() .'/style.css', array('parent-style'));

        /* custom.php containing theme color CSS */
        wp_register_style( 'custom-css', CHILD_TEMPLATE_DIR.'/css/custom.php');
        wp_enqueue_style( 'custom-css' );
     }
 }
 add_action('wp_enqueue_scripts', 'embedd_custom_javascripts_and_css');


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

require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-top-transcribers/tct-top-transcribers-widget.php'); // Adds the top-transcribers-widget
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-progress-line-chart/tct-progress-line-chart-widget.php'); // Adds the line-chart-widget
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-home-stats/tct-home-stats-widget.php'); // Adds the widget for statistic numbers on a project landingpage
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-icon-links/tct-icon-links-widget.php'); // Adds the widget for icon links
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-news-container/tct-news-container-widget.php'); // Adds the widget for news container
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-search-documents/tct-search-documents-widget.php'); // Adds the widget for document search

function add_custom_widget_collection($folders){
    $folders[] = CHILD_TEMPLATE_DIR.'admin/inc/custom_widgets/';
    return $folders;
}
add_filter('siteorigin_widgets_widget_folders', 'add_custom_widget_collection');


// ### HOOKS ### //
add_action( 'um_profile_header_cover_area', 'my_profile_header_cover_area', 10, 1 );
function my_profile_header_cover_area( $args ) {
    echo "<div class='tct-user-banner ".um_user('role')."'>".ucfirst(um_user('role'))."</div>\n";
    $acs = [];
    if(sizeof($acs)>0){
        echo "<div class=\"achievments\">\n";
        foreach($acs as $ac){
            echo "<div title=\"".$ac['campaign_title']."\"class=\"".$ac['badge']."\"></div>\n";
        }
        echo "</div>\n";
    }
}

add_filter( 'upload_size_limit', 'increase_upload' );
function increase_upload( $bytes )
{
  return 210000000; // 200 megabyte
}
