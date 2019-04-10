<?php
/* 
Shortcode: get_stories
Description: Gets stories from the API and displays them
*/
function _TCT_get_stories( $atts ) {   
    global $wp;
    // get Stories from the API
    $json = file_get_contents(network_home_url()."tp-api/Story/all");
    $stories = json_decode($json, true);
    $content = "";
    foreach ($stories as $story){
        $content .= "<a href='".home_url( $wp->request )."/story?id=".$story['StoryId']."'>".$story['dcTitle']."</a></br>";
    }
    echo $content;
}
add_shortcode( 'get_stories', '_TCT_get_stories' );
?>