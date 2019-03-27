<?php
/*
 * Plugin Name: Transcribathon Image-Viewer IIIF
 * Description: Image-Viewer for transcription jobs
 * Version: 1.0
 * Author: Piktoresk, Olaf Baldini (based on iviewer Widget by Dmitry Petrov)
 */

// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}




function tct_image_viewer($atts) {
	global $sitepress;
	$current_lang = $sitepress->get_current_language();
	   extract(shortcode_atts(array(
		  'transcribe' => false,
		  'height' => '400px',
		  'width' => '100%',
		  'post' => '',
		  'prev' => '',
		  'next' => ''
		), $atts));

	$myID = "tct_iv_".uniqid(rand()).date('HisYmd');

	if(isset($post) && trim($post) != ""){
		if(has_post_thumbnail($post)){
			$thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id($post),'full', true);
			$imagefile = $thumb_url[0];
		}
	}
	$ret = "<div class=\"tct_iv_wrap\">\n";

	$ret .= "<div id=\"fsgo\" class=\"zoom-in\">\n";

		if(isset($prev) && trim($prev) != ""){ $style = ""; }else{ $style=" style=\"display:none;\""; }
		$ret .= "<a id=\"ap_".$myID."\" data-rel=\"".$prev."\" ".$style." data-lang=\"".$current_lang."\" href=\"\" class=\"tct_viewer_prev\"></a>";


		$ret .= "<div id=\"".$myID."\" class=\"tct-image-viewer fullscreen\" rel=\"".$imagefile."\" style=\"width: 100%;\"></div>\n";
		if(!is_user_logged_in()){
			$ret .= "<div id=\"transscriber-huge\" class=\"inact lgd transscriber-huge\" style='top:50px;'>\n";
		}else{
			$ret .= "<div id=\"transscriber-huge\" class=\"inact transscriber-huge\" style='top:50px;'>\n";
		}
			$ret .= "<div class=\"dragbar\">Edit transcription:<a id=\"he-close\"></a></div>\n";
			$ret .= "<div id=\"hugeholder\">\n";
			$ret .= "<div id=\"mytoolbar\"></div>\n";
			$ret .= "<div id=\"huge-transcibe-area\" class=\"tct-transcriberfield\">...</div>\n";
				$ret .= "<div class=\"bottom-holder\">\n";
					$ret .= "<div class=\"saved-info\">"._x('Transcription saved','Documents-Single Document','transcribathon')."</div>\n";
					$ret .= "<button name=\"huge-transcription-saver\" id=\"huge-transcription-saver\" onclick=\"svTcT(jQuery(this)); return false;\" style=\"display:inline; float:none;\">"._x('Save transcription','Documents-Single Document','transcribathon')."</button>\n";
				$ret .= "</div>\n";
			$ret .= "</div>\n";
		$ret .= "</div>\n";

		if(isset($next) && trim($next) != ""){  $style = ""; }else{ $style=" style=\"display:none;\""; }
		$ret .= "<a id=\"an_".$myID."\" data-rel=\"".$next."\" ".$style." data-lang=\"".$current_lang."\" href=\"\" class=\"tct_viewer_next\"></a>";


	$ret .= "</div>\n";
	$ret .= "</div>\n"; //tct_iv_wrap
	//$ret .= "<a href=\"\" onclick=\"toggleFull(document.getElementById('".$myID."')); return false;\" class=\"tipp\">"._x('Enhance your transcribing experience by using full-screen mode','Viewer','transcribathon')."</a>\n";
	$ret .= "<a href=\"\" onclick=\"toggleFull(document.getElementById('fsgo')); return false;\" class=\"tipp\">"._x('Enhance your transcribing experience by using full-screen mode','Viewer','transcribathon')."</a>\n";
	return $ret;

}
add_shortcode('tct-viewer-iiif', 'tct_image_viewer');


function add_tct_image_viewer_iiif_js() {
	wp_deregister_script('ui-js');
    wp_register_script('ui-js', plugin_dir_url(__FILE__ ).'assets/js/jquery-ui.min.js');
    wp_enqueue_script('ui-js');

	wp_deregister_script('openseadragon-js');
    	wp_register_script('openseadragon-js', plugin_dir_url(__FILE__ ).'assets/js/openseadragon.js');
    	wp_enqueue_script('openseadragon-js');

	/* wp_deregister_script('magnifiq-js');
    wp_register_script('magnifiq-js', plugin_dir_url(__FILE__ ).'assets/js/magnific_popup.js');
    wp_enqueue_script('magnifiq-js');
	 */
	/* wp_deregister_script('bigscreen-js');
    wp_register_script('bigscreen-js', plugin_dir_url(__FILE__ ).'assets/js/bigscreen.js');
    wp_enqueue_script('bigscreen-js'); */

	wp_deregister_script('fullscreen-js');
    wp_register_script('fullscreen-js', plugin_dir_url(__FILE__ ).'assets/js/fullscreen.js');
    wp_enqueue_script('fullscreen-js');


	wp_deregister_script('viewer-js');
    wp_register_script('viewer-js', plugin_dir_url(__FILE__ ).'assets/js/jquery.iviewer.js');
    wp_enqueue_script('viewer-js');
	wp_deregister_script('tct-image-viewer-js');
    wp_register_script('tct-image-viewer-js', plugin_dir_url(__FILE__ ).'assets/js/tct-image-viewer.js');
    wp_enqueue_script('tct-image-viewer-js');

	wp_register_style( 'tct-image-viewer-fullscreen-css', plugin_dir_url(__FILE__ ).'assets/css/fullscreen.css');
    wp_enqueue_style( 'tct-image-viewer-fullscreen-css' );
	wp_register_style( 'tct-image-viewer-iviewer-css', plugin_dir_url(__FILE__ ).'assets/css/jquery.iviewer.css');
    wp_enqueue_style( 'tct-image-viewer-iviewer-css' );
/* 	wp_register_style( 'tct-image-viewer-magnific-css', plugin_dir_url(__FILE__ ).'assets/css/magnific_popup.css');
    wp_enqueue_style( 'tct-image-viewer-magnific-css' );  */

}
add_action('wp_enqueue_scripts', 'add_tct_image_viewer_iiif_js');
