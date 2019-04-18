<?php
/* 
Shortcode: item_page_test
Description: Gets item data and builds the item page
*/

// include required files
include($_SERVER["DOCUMENT_ROOT"].'/wp-load.php');

function _TCT_item_page_test( $atts ) {  
    if (isset($_GET['id']) && $_GET['id'] != "") {
        // get Item data from API
        $postdata = http_build_query(
            array(
                'key' => 'testKey',
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $json = file_get_contents(get_home_url()."/tp-api/Item/".$_GET['id'], false, $context);

        //$json = file_get_contents(get_home_url()."/tp-api/Item/".$_GET['id']);
        $data = json_decode($json, true);
        $data = $data[0];

        // build Item page content
        $content = "";
        $content .= "        <div class='item-page-slider'>
        <div><img src='".$data['ImageLink']."'></div>
        <div><img src='".$data['ImageLink']."'></div>
        <div><img src='".$data['ImageLink']."'></div>
        <div><img src='".$data['ImageLink']."'></div>
        <div><img src='".$data['ImageLink']."'></div>
        <div><img src='".$data['ImageLink']."'></div>
        <div><img src='".$data['ImageLink']."'></div>
        <div><img src='".$data['ImageLink']."'></div>
                </div>";
         $content .= "<script>jQuery(document).ready(function(){
                        jQuery('.item-page-slider').slick({
                            dots: true,
                            infinite: true,
                            arrows: false,
                            speed: 300,
                            slidesToShow: 4,
                            slidesToScroll: 4,
                            responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3,
                                infinite: true,
                                dots: true
                                }
                            },
                            {
                                breakpoint: 600,
                                settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2
                                }
                            },
                            {
                                breakpoint: 480,
                                settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                                }
                            }
                            // You can unslick at a given breakpoint now by adding:
                            // settings: 'unslick'
                            // instead of a settings object
                            ]
                        });
                    });</script>";
        $content .= "<div class='panel-container'>";
            
            // Image section
            $content .= "<div class='panel-left'>
                            <img src='".$data['ImageLink']."'>
                        </div>";

            // Resize slider
            $content .= "<div class='splitter'>
                        </div>";

            // Info/Transcription section
            $content .= "<div class='panel-right'>";
                $content .= '<div class="tab">';
                    $content .= '<input id="editor-icon" type="image" width="58" height="58" class="tablinks"
                            onclick="switchTab(\'editor-tab\')"
                                src="http://simpleicon.com/wp-content/uploads/pencil.svg" alt="Edit">';
                    $content .= '<input id="settings-icon" type="image" width="58" height="58" class="tablinks"
                            onclick="switchTab(event, \'settings-tab\')"
                                src="http://simpleicon.com/wp-content/uploads/equalizer.svg" alt="Equilize">';
                    $content .= '<input id="info-icon" type="image" width="58" height="58" class="tablinks"
                            onclick="switchTab(event, \'info-tab\')"            
                                src="https://cdn.onlinewebfonts.com/svg/img_180137.svg" alt="Info">';
                    $content .= '<input id="tags-icon" type="image" width="58" height="58" class="tablinks"
                            onclick="switchTab(event, \'tags-tab\')"
                                src="http://simpleicon.com/wp-content/uploads/tag2.svg" alt="Tag">';
                    $content .= '<input id="help-icon" type="image" width="58" height="58" class="tablinks"
                            onclick="switchTab(event, \'help-tab\')"            
                                src="http://simpleicon.com/wp-content/uploads/question_mark_1.svg" alt="Query">';
                $content .= '</div>';

                
                $content .= "<div id='panel-right-content' class='panel-right-tab-menu'>";
                    
                    $content .= "<div id='editor-tab' class='tabcontent'>";
                        $content .= "<p class='item-view-section-headline'>TRANSCRIPTION</p>";                 
                    $content .= "</div>";

                    $content .= "<div id='settings-tab' class='tabcontent' style='display:none;'>";
                        $content .= "<p class='item-view-section-headline'>ADVANCED IMAGE SETTINGS</p>"; 
                    $content .= "</div>";    

                    $content .= "<div id='info-tab' class='tabcontent' style='display:none;'>";

                        $content .= "<p class='item-view-section-headline'>DOCUMENT META DATA</p>";
                        $content .= "<p class='item-view-section-headline'>Personal War Diary</p>";
                        $content .= "<p class='item-view-property-sideline'><strong>HMS Comet</strong></p></br>";
                        $content .= "<span class='item-view-property-value'>".$data['Description']."</span></br>";

                        $content .= "<p class='item-view-property-headline'>People</p>";
                        $content .= "<span class='item-view-property-key'>Contributor: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Subject: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['StoryPlaceName']."</span></br>";

                        $content .= "<p class='item-view-property-headline'>Classifications</p>";
                        $content .= "<span class='item-view-property-key'>Type: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Subject: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['StoryPlaceName']."</span></br>";

                        $content .= "<p class='item-view-section-headline'>EXTENDED INFORMATION</p>";

                        $content .= "<p class='item-view-property-headline'>Properties</p>";
                        $content .= "<span class='item-view-property-key'>Language: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";

                        $content .= "<p class='item-view-property-headline'>Time</p>";
                        $content .= "<span class='item-view-property-key'>Creation date: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        
                        $content .= "<p class='item-view-property-headline'>Provenanace</p>";
                        $content .= "<span class='item-view-property-key'>Source: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Provenance: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Identifier: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Institution: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Provider: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Providing country: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        $content .= "<span class='item-view-property-key'>First published in Europeana: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Last updated in Europeana: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        
                        $content .= "<p class='item-view-property-headline'>References and relations</p>";
                        $content .= "<span class='item-view-property-key'>Location: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['TranscriptionId']."</span></br>";

                        $content .= "<p class='item-view-property-headline'>Location</p>";
                        $content .= "<span class='item-view-property-key'>Dataset: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['PlaceId']."</span></br>";

                        $content .= "<p class='item-view-property-headline'>Entities</p>";
                        $content .= "<span class='item-view-property-key'>Concept term: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['ImageLink']."</span></br>";
                    $content .= "</div>";

                    $content .= "<div id='tags-tab' class='tabcontent' style='display:none;'>";
                        $content .= "<p>test... tags tab</p>";                    
                    $content .= "</div>";

                    $content .= "<div id='help-tab' class='tabcontent' style='display:none;'>";
                        $content .= "<p>test... help tab</p>";                    
                    $content .= "</div>";

                $content .= "</div>";
            $content .= '</div>
                    </div>';
        $content .= '<script>
                        jQuery(".panel-left").resizable({
                            handleSelector: ".splitter",
                            resizeHeight: false
                        });
                    </script>';

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
        $content .= "</div> 
                </div>";
        echo $content;
    }
}
add_shortcode( 'item_page_test', '_TCT_item_page_test' );
?>