<?php
/* transcribathon functions and definitions */

add_filter( 'solr_scheme', function(){ return 'http'; });
define( 'SOLR_PATH', '/home/enrich/solr-7.7.1/bin/solr' );

// define constants
define('CHILD_TEMPLATE_DIR', dirname( get_bloginfo('stylesheet_url')) );
define( 'TCT_THEME_DIR_PATH', plugin_dir_path( __FILE__ ) );
/* Disable WordPress Admin Bar for all users but admins. */
show_admin_bar(false);
// change url of login logo link
add_filter( 'login_headerurl', 'custom_loginlogo_url');

function custom_loginlogo_url($url) {

return 'https://transcribathon.eu';

}
function my_login_logo_one() { 
    ?> 
    <style type="text/css"> 
    body.login div#login h1 a {
    background-image: none, url(https://transcribathon.eu/wp-content/uploads/2020/02/transcribathon.png);
    margin: 0;
    background-size: unset;
    width: 100%;
    }
    body.login div#login h1 a :focus{
        outline:none;
    }
    </style>
    <?php 
    } add_action( 'login_enqueue_scripts', 'my_login_logo_one' );
// Custom Theme-Settings for Transcribathon
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_themesettings/tct-themesettings.php');

// ### ADMIN PAGES ### //
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_admin_pages/teams-admin-page.php'); // Adds teams admin page
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_admin_pages/campaigns-admin-page.php'); // Adds campaigns admin page
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_admin_pages/documents-admin-page.php'); // Adds documents admin page
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_admin_pages/datasets-admin-page.php'); // Adds documents admin page

// Custom shortcodes
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/story_page.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/item_page.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/item_page_test.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/item_page_test_ad.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/item_page_test_iiif.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/tutorial_item_slider.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/tutorial_menu.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/documents_map.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_shortcodes/team.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_profiletabs/transcriptions.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_profiletabs/contributions.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_profiletabs/achievements.php');
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_profiletabs/teams_runs.php');

// Custom posts
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_posts/tct-news/tct-news.php'); // Adds custom post-type: news
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_posts/tct-tutorial/tct-tutorial.php'); // Adds custom post-type: news
// Image settings
add_image_size( 'news-image', 300, 200, true );
// Image settings
add_image_size( 'tutorial-image', 1600, 800, true );

// Embedd custom Javascripts and CSS
function embedd_custom_javascripts_and_css() {
    global $post;
     if (!is_admin() && $GLOBALS['pagenow'] != 'wp-login.php') {
        /* jQuery */
        wp_enqueue_script( 'jquery' );	

        /* custom JS and CSS*/
        /* openseadragon */
        wp_enqueue_script( 'osd', CHILD_TEMPLATE_DIR . '/js/openseadragon.js');
        /*osdSelection plugin*/
        wp_enqueue_script('osdSelect', CHILD_TEMPLATE_DIR . '/js/openseadragonSelection.js');
        
           
        wp_enqueue_script( 'custom', CHILD_TEMPLATE_DIR . '/js/custom.js');
        wp_enqueue_style('child-style', get_stylesheet_directory_uri() .'/style.css', array('parent-style'));
       
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

        /* jQuery pagination */
        wp_enqueue_script( 'pagination', CHILD_TEMPLATE_DIR . '/js/pagination.min.js');


        /* custom.php containing theme color CSS */
        wp_register_style( 'custom-css', CHILD_TEMPLATE_DIR.'/css/custom.php');
        wp_enqueue_style( 'custom-css' );

	/* mapbox js and style*/
        wp_enqueue_script( 'mapbox-gl', 'https://api.tiles.mapbox.com/mapbox-gl-js/v1.2.0/mapbox-gl.js', null, null, true );
	wp_enqueue_style('mapblox-gl', CHILD_TEMPLATE_DIR . '/css/mapbox-gl.css'); 
     }
 }
 add_action('wp_enqueue_scripts', 'embedd_custom_javascripts_and_css');

 wp_register_script( 'my-script', '/myscript_url' );
 wp_enqueue_script( 'my-script' );
 $translation_array = array( 
                        'home_url' => home_url(),
                        'network_home_url' => network_home_url() 
                    );
 //after wp_enqueue_script
 wp_localize_script( 'my-script', 'WP_URLs', $translation_array );


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
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-horizontal-line-hr/tct-horizontal-line-widget.php'); // Adds the widget for headline (hr)
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-tutorial-slider/tct-tutorial-slider-widget.php'); // Adds the widget for tutorial slider
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-storyboxes/tct-storyboxes-widget.php'); // Adds the widget for storyboxes
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-menulist/tct-menulist-widget.php'); // Adds the widget for menulist
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-headline/tct-headline-widget.php'); // Adds the widget for headline
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-colcontent/tct-colcontent-widget.php'); // Adds the widget for displaying content in different columns
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-boxes/tct-boxes-widget.php'); // Adds the widget for feature boxes
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-button/tct-button-widget.php'); // Adds the widget for a preformatted button
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-barchart/tct-barchart-widget.php'); // Adds the widget for a preformatted button
require_once(TCT_THEME_DIR_PATH.'admin/inc/custom_widgets/tct-numbers/tct-numbers-widget.php'); // Adds the widget for a preformatted button

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

add_action( 'um_registration_complete', 'transfer_new_user', 10, 2 );
function transfer_new_user( $user_id, $args ) {
    $url = home_url()."/tp-api/users";
    $requestType = "POST";
    $requestData = array(
        'WP_UserId' => $user_id,
        'Role' => "Member",
        'WP_Role' => "Subscriber",
        'Token' => "fdfsdkfjk"
    );
    
    // Execude http request
    include TCT_THEME_DIR_PATH.'admin/inc/custom_scripts/send_api_request.php';
}

// ### Functions ### //
function tct_generatePassword($passwordlength = 8,$numNonAlpha = 0,$numNumberChars = 0, $useCapitalLetter = false ) {
    $numberChars = '123456789';
    $specialChars = '!$%&=?*-:;.,+~@_';
    $secureChars = 'abcdefghjkmnpqrstuvwxyz';
    $stack = '';
    $stack = $secureChars;
    if ( $useCapitalLetter == true )
        $stack .= strtoupper ( $secureChars );
    $count = $passwordlength - $numNonAlpha - $numNumberChars;
    $temp = str_shuffle ( $stack );
    $stack = substr ( $temp , 0 , $count );
    if ( $numNonAlpha > 0 ) {
        $temp = str_shuffle ( $specialChars );
        $stack .= substr ( $temp , 0 , $numNonAlpha );
    }
    if ( $numNumberChars > 0 ) {
        $temp = str_shuffle ( $numberChars );
        $stack .= substr ( $temp , 0 , $numNumberChars );
    }
    $stack = str_shuffle ( $stack );
    return $stack;
} 

/*
add_action( 'wp_enqueue_scripts', 'enqueue_theme_css' );


function enqueue_theme_css()
{
    wp_enqueue_style(
        'default',
        '/wp-content/themes/vantage/style.css'
    );
    wp_enqueue_style(
        'default',
        '/wp-content/themes/transcribathon/scss/style.css'
    );
}*/