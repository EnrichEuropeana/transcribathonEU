<?php
/* 
Shortcode: item_page_test_ad
Description: Gets item data and builds the item page
*/

// include required files
include($_SERVER["DOCUMENT_ROOT"].'/wp-load.php');

function _TCT_item_page_test_ad( $atts ) {  
    echo "<style type='text/css'>
    
            </style>";
    if (isset($_GET['id']) && $_GET['id'] != "") {
        // Set request parameters
        $data = array(
            'key' => 'testKey'
        );
        $url = network_home_url()."/tp-api/Item/".$_GET['id'];
        $requestType = "POST";
    
        // Execude request
        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

        // Display data
        $data = json_decode($result, true);
        $data = $data[0];

        // build Item page content
        $content = "";
        
        // Top image slider 
        $content .= "<div class='item-page-slider'>
                        <div><img src='".$data['ImageLink']."'></div>
                        <div><img src='".$data['ImageLink']."'></div>
                        <div><img src='".$data['ImageLink']."'></div>
                        <div><img src='".$data['ImageLink']."'></div>
                        <div><img src='".$data['ImageLink']."'></div>
                        <div><img src='".$data['ImageLink']."'></div>
                        <div><img src='".$data['ImageLink']."'></div>
                        <div><img src='".$data['ImageLink']."'></div>
                    </div>";
        // Image slider JavaScript
         $content .= "<script>
                        jQuery(document).ready(function(){
                            jQuery('.item-page-slider').slick({
                                dots: true,
                                infinite: true,
                                arrows: false,
                                speed: 300,
                                slidesToShow: 6,
                                slidesToScroll: 6,
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
                        });
                    </script>";

        // Splitscreen container
        $content .= "<div id='item-container' class='panel-container-horizontal'>";
            
            // Image section
            $content .= "<div id='item-image-section' class='panel-left'>
                            <img src='".$data['ImageLink']."'>
                        </div>";

            // Resize slider
            $content .= "<div id='item-splitter' class='splitter-vertical'>
                        </div>";

            // Info/Transcription section
            $content .= "<div id='item-data-section' class='panel-right'>";
                $content .= "<div id='item-data-header'>";
                    // Tab menu
                    $content .= '<div id="item-tab-menu" class="tab-menu">';
                        $content .= '<i class="theme-color-hover fa fa-pencil tablinks active"
                            onclick="switchItemTab(event, \'editor-tab\')"></i>';
                            
                        $content .= '<i class="theme-color-hover fa fa-sliders tablinks"
                            onclick="switchItemTab(event, \'settings-tab\')"></i>';

                        $content .= '<i class="theme-color-hover fa fa-info-circle tablinks"
                            onclick="switchItemTab(event, \'info-tab\')"></i>';

                        $content .= '<i class="theme-color-hover fa fa-globe tablinks"
                            onclick="switchItemTab(event, \'tags-tab\')"></i>';

                        $content .= '<i class="theme-color-hover fa fa-question-circle tablinks"
                            onclick="switchItemTab(event, \'help-tab\')"></i>';

                        $content .= '<i class="theme-color-hover fa fa-laptop tablinks"
                            onclick="switchItemTab(event, \'enrichment-tab\')"></i>';
                        /* //old linkup...for icons
                        $content .= '<input id="help-icon" type="image" class="tablinks"
                                onclick="switchItemTab(event, \'help-tab\')"            
                                    src="http://simpleicon.com/wp-content/uploads/question_mark_1.svg" alt="Help">'; */
                    $content .= '</div>';

                    // View switcher
                    $content .= '<div class="view-switcher">';
                        $content .= '<input id="horizontal-split" type="image" class="view-switcher-button active"
                                onclick="switchItemView(event, \'horizontal\')"
                                    src="'.CHILD_TEMPLATE_DIR.'/images/split-left.png" alt="Editor">';
                        $content .= '<input id="vertical-split" type="image" class="view-switcher-button"
                                onclick="switchItemView(event, \'vertical\')"
                                    src="'.CHILD_TEMPLATE_DIR.'/images/split-top.png" alt="Settings">';
                        $content .= '<input id="popout" type="image" class="view-switcher-button"
                                onclick="switchItemView(event, \'popout\')"            
                                    src="'.CHILD_TEMPLATE_DIR.'/images/popout.png" alt="Info">';
                    $content .= '</div>';
                $content .= "</div>";
                
                // Tab content
                $content .= "<div id='item-data-content' class='panel-right-tab-menu'>";
                    
                    // Editor tab
                    $content .= "<div id='editor-tab' class='tabcontent'>";

                        // Current transcription
                        $content .= "<p class='theme-color item-view-section-headline'>TRANSCRIPTION</p>";
                        $currentTranscription = "";
                        $transcriptionList = [];
                        foreach ($data["Transcriptions"] as $transcription) {
                            if ($transcription['CurrentVersion'] == "1") {
                                $currentTranscription = $transcription;
                            }
                            else {
                                array_push($transcriptionList, $transcription);
                            }
                        }
                        
                        $content .= '<p id="item-view-current-transcription">'.$currentTranscription["Text"].'</p>';

                        // Description
                        $content .= "<p class='theme-color item-view-section-headline'>Description</p>";
                        $content .= '<textarea id="item-view-description-text" rows="4">';
                        $content .= $data['Description'];
                        $content .= '</textarea>';


                        $content .= '<p class="theme-color item-view-section-headline">TRANSCRIPTION History</p></br>';
                        $i = 0;
                        foreach ($transcriptionList as $transcription) {
                            $content .= '<button type="button" class="trancription-toggle" data-toggle="collapse" data-target="#transcription-'.$i.'">'.$transcription['Timestamp'].'</button>';
                                $content .= '<div id="transcription-'.$i.'" class="collapse transcription-history-collapse-content">';
                                    $content .= '<p id="item-view-current-transcription">'.$transcription['Text'].'</p>';
                                    $content .= '<input class="transcription-comparison-button" type="button" onClick="compareTranscription(\''.$transcriptionList[0]['Text'].'\', \''.$currentTranscription['Text'].'\','.$i.')" value="Compare to current transcription">';
                                    $content .= '<p id="transcription-comparison-output-'.$i.'" class="transcription-comparison-output"></p>';
                                $content .= '</div>';
                            $i++;
                        }
                    $content .= "</div>";

                    // Image settings tab
                    $content .= "<div id='settings-tab' class='tabcontent' style='display:none;'>";
                        $content .= "<p class='theme-color item-view-section-headline'>ADVANCED IMAGE SETTINGS</p>"; 
                    $content .= "</div>";    

                    // Info tab
                    $content .= "<div id='info-tab' class='tabcontent' style='display:none;'>";
                        $content .= "<p class='theme-color item-view-section-headline'>DOCUMENT META DATA</p>";
                        $content .= "<p class='theme-color item-view-section-headline'>Personal War Diary</p>";
                        $content .= "<p class='item-view-property-sideline'><strong>HMS Comet</strong></p></br>";
                        $content .= "<span class='item-view-property-value'>".$data['Description']."</span></br>";

                        $content .= "<p class='theme-color item-view-property-headline'>People</p>";
                        $content .= "<span class='item-view-property-key'>Contributor: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Contributor']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Subject: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['StoryPlaceName']."</span></br>";

                        $content .= "<p class='theme-color item-view-property-headline'>Classifications</p>";
                        $content .= "<span class='item-view-property-key'>Type: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Subject: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['StoryPlaceName']."</span></br>";

                        $content .= "<p class='theme-color item-view-section-headline'>PROPERTIES</p>";
                        $content .= "<span class='item-view-property-key'>Language: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Languauges'][0]."</span></br>";
                        $content .= "<span class='item-view-property-key'>Keyword: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['SearchText']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Link: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Link']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Category: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Title']."</span></br>";

                        $content .= "<p class='theme-color item-view-property-headline'>Time</p>";
                        $content .= "<span class='item-view-property-key'>Creation date: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['Timestamp']."</span></br>";
                        
                        $content .= "<p class='theme-color item-view-property-headline'>Provenanace</p>";
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
                        $content .= "<span class='item-view-property-value'>".$data['DateStart']."</span></br>";
                        $content .= "<span class='item-view-property-key'>Last updated in Europeana: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['DateEnd']."</span></br>";
                        
                        $content .= "<p class='theme-color item-view-property-headline'>References and relations</p>";
                        $content .= "<span class='item-view-property-key'>Location: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['TranscriptionId']."</span></br>";

                        $content .= "<p class='theme-color item-view-property-headline'>Location</p>";
                        $content .= "<span class='item-view-property-key'>Dataset: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['PlaceId']."</span></br>";

                        $content .= "<p class='theme-color item-view-property-headline'>Entities</p>";
                        $content .= "<span class='item-view-property-key'>Concept term: </span>";
                        $content .= "<span class='item-view-property-value'>".$data['ImageLink']."</span></br>";
                    $content .= "</div>";

                    // Tagging tab
                    $content .= "<div id='tags-tab' class='tabcontent' style='display:none;'>";
                        $content .= "<p>test... tags tab</p>";                    
                    $content .= "</div>";

                    // Help tab
                    $content .= "<div id='help-tab' class='tabcontent' style='display:none;'>";
                        $content .= "<p>test... help tab</p>";                    
                    $content .= "</div>";

                    // Automatic enrichment tab
                    $content .= "<div id='enrichment-tab' class='tabcontent' style='display:none;'>";
                        $content .= "<p>test... automatic enrichment tab</p>";                    
                    $content .= "</div>";

                $content .= "</div>";
            $content .= '</div>
                    </div>';
        
        // Split screen JavaScript
        $content .= '<script>
                        jQuery("#item-image-section").resizable({
                            handleSelector: "#item-splitter",
                            resizeHeight: false
                        });
                    </script>';

        // Data dump at the bottom (Can be removed)
            
                       
        $content .= "<h2 class=\"theme-color-background comments-head\">Notes and questions</h2>
        <div id=\"single-comments-wrapper\">
            

            <div id=\"comments\" class=\"comments-area\">

                    <div id=\"respond\" class=\"comment-respond\">
                <h3 id=\"reply-title\" class=\"comment-reply-title\">Leave a note or a question <small><a rel=\"nofollow\" id=\"cancel-comment-reply-link\" href=\"/en/documents/id-19044/item-223349/#respond\" style=\"display:none;\">Cancel reply</a></small></h3>			
                    <form action=\"https://transcribathon.com/wp-comments-post.php\" method=\"post\" id=\"commentform\" class=\"comment-form\">
                        <p class=\"logged-in-as\"><a href=\"https://transcribathon.com/wp-admin/profile.php\" aria-label=\"Logged in as aditya wuyyuru. Edit your profile.\">Logged in as aditya wuyyuru</a>. <a href=\"https://transcribathon.com/wp-login.php?action=logout&amp;redirect_to=https%3A%2F%2Ftranscribathon.com%2Fen%2Fdocuments%2Fid-19044%2Fitem-223349%2F&amp;_wpnonce=cfefe02d82\">Log out?</a></p><textarea id=\"comment\" name=\"comment\" aria-required=\"true\"></textarea><input name=\"wpml_language_code\" type=\"hidden\" value=\"en\" /><p class=\"form-submit\"><input name=\"submit\" type=\"submit\" id=\"submit\" class=\"submit\" value=\"Save note\" /> <input type='hidden' name='comment_post_ID' value='296152' id='comment_post_ID' />
                <input type='hidden' name='comment_parent' id='comment_parent' value='0' />
                </p><input type=\"hidden\" id=\"_wp_unfiltered_html_comment_disabled\" name=\"_wp_unfiltered_html_comment_disabled\" value=\"1f491b0ac2\" />
                <script>(function(){if(window===window.parent){document.getElementById('_wp_unfiltered_html_comment_disabled').name='_wp_unfiltered_html_comment';}})();</script>
                    </form>
                    </div><!-- #respond -->
                
            
                
            </div><!-- #comments .comments-area -->
        </div> ";

        $content .= "</div> 
                </div>";
        echo $content;
    }
}
add_shortcode( 'item_page_test_ad', '_TCT_item_page_test_ad' );
?>