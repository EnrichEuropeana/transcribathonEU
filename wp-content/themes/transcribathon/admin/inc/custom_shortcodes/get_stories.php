<?php
/* 
Shortcode: get_stories
Description: Gets stories from the API and displays them
*/
function _TCT_get_stories( $atts ) {   
    global $wp;
    // Set Post content
    $data = array(
        'key' => 'testKey'
    );
    $url = network_home_url()."tp-api/Story/all";
    $requestType = "POST";

    include dirname(__FILE__) . '/../custom_scripts/send_api_request.php';

    $stories = json_decode($result, true);
    $content = "";
    foreach ($stories as $story){
        $content .= "<a href='".home_url( $wp->request )."/story?id=".$story['StoryId']."'>".$story['dcTitle']."</a></br>";
    }
    echo $content;
}
add_shortcode( 'get_stories', '_TCT_get_stories' );
?>