<?php
/* 
Shortcode: item_page
Description: Gets item data and builds the item page
*/

// include required files
include($_SERVER["DOCUMENT_ROOT"].'/wp-load.php');

function _TCT_item_page( $atts ) {  
    if (isset($_GET['id']) && $_GET['id'] != "") {
        // get Item data from API
        $json = file_get_contents(get_home_url()."/tp-api/Item/".$_GET['id']);
        $data = json_decode($json, true);
        $data = $data[0];

        // build Item page content
        $content = "";

        $content .= "<p class='item-view-property-headline'>DOCUMENT META DATA</p>";
        $content .= "<p class='item-view-property-headline'>Personal Diary</p>";
        $content .= "<p1 class='item-view-property-sideline'>HMS Comet</p1></br>";
        //$content .= "<nobr class='item-view-property-key'>Contributor: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Description']."</nobr></br>";

        $content .= "<p class='item-view-property-headline'>People</p>";
        $content .= "<nobr class='item-view-property-key'>Contributor: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";
        $content .= "<nobr class='item-view-property-key'>Subject: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['StoryPlaceName']."</nobr></br>";

        $content .= "<p class='item-view-property-headline'>Classifications</p>";
        $content .= "<nobr class='item-view-property-key'>Type: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";
        $content .= "<nobr class='item-view-property-key'>Subject: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['StoryPlaceName']."</nobr></br>";

        $content .= "<p class='item-view-property-headline'>EXTENDED INFORMATION</p>";

        $content .= "<p class='item-view-property-headline'>Properties</p>";
        $content .= "<nobr class='item-view-property-key'>Language: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";

        $content .= "<p class='item-view-property-headline'>Time</p>";
        $content .= "<nobr class='item-view-property-key'>Creation date: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";

        $content .= "<p class='item-view-property-headline'>Provenanace</p>";
        $content .= "<nobr class='item-view-property-key'>Source: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";
        $content .= "<nobr class='item-view-property-key'>Provenance: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";
        $content .= "<nobr class='item-view-property-key'>Identifier: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";
        $content .= "<nobr class='item-view-property-key'>Institution: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";
        $content .= "<nobr class='item-view-property-key'>Provider: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";
        $content .= "<nobr class='item-view-property-key'>Providing country: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";
        $content .= "<nobr class='item-view-property-key'>First published in Europeana: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";
        $content .= "<nobr class='item-view-property-key'>Last updated in Europeana: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";
        
        $content .= "<p class='item-view-property-headline'>References and relations</p>";
        $content .= "<nobr class='item-view-property-key'>Location: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";

        $content .= "<p class='item-view-property-headline'>Location</p>";
        $content .= "<nobr class='item-view-property-key'>Dataset: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";

        $content .= "<p class='item-view-property-headline'>Entities</p>";
        $content .= "<nobr class='item-view-property-key'>Concept term: </nobr>";
        $content .= "<nobr class='item-view-property-value'>".$data['Title']."</nobr></br>";



        foreach ($data as $key => $value){
            if (is_array($value)){
                $content .= "</br>";
                $content .= $key.": ";
                $content .= "</br>";
                foreach ($value as $element){
                    foreach ($element as $innerKey => $innerValue){
                        $content .= "{$innerKey} => {$innerValue} </br>";
                    }
                    $content .= "</br>";
                }
            }
            else {
                $content .= "{$key} => {$value} </br>";
            }
        }
        echo $content;
    }
}
add_shortcode( 'item_page', '_TCT_item_page' );
?>