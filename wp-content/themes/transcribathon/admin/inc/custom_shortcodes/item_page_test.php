<?php
/* 
Shortcode: item_page_test
Description: Gets item data and builds the item page
*/

// include required files
include($_SERVER["DOCUMENT_ROOT"].'/wp-load.php');

function _TCT_item_page_test( $atts ) {
    if (isset($_GET['id']) && $_GET['id'] != "") {
        // Set request parameters
        $data = array(
            'key' => 'testKey'
        );
        $url = network_home_url()."/tp-api/Item/".$_GET['id'];
        $requestType = "POST";
    
        // Execude http request
        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

        // Display data
        $data = json_decode($result, true);
        $data = $data[0];

        // Build Item page content
        $content = "";

        // Image viewer
        $imageViewer = "";
            $imageViewer .= "<img src='".$data['ImageLink']."'>";

        // Editor tab
        $editorTab = "";
            // Current transcription
            $editorTab .= "<p class='theme-color item-page-section-headline'>TRANSCRIPTION</p>";
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
            $editorTab .= '<p id="item-page-current-transcription">'.$currentTranscription['Text'].'</p>';

            // Description
            $editorTab .= "<p class='theme-color item-page-section-headline'>";
                $editorTab .= "<Description";
            $editorTab .= "<</p>";
            $editorTab .= '<textarea id="item-page-description-text" rows="4">';
                $editorTab .= $data['Description'];
            $editorTab .= '</textarea>';

            // Transcription history
            $editorTab .= '<p class="theme-color item-page-section-headline">TRANSCRIPTION History</p></br>';
            $i = 0;
            foreach ($transcriptionList as $transcription) {
                $editorTab .= '<button type="button" class="trancription-toggle" data-toggle="collapse" data-target="#transcription-'.$i.'">';
                    $editorTab .= $transcription["Timestamp"];
                $editorTab .= '</button>';
                    $editorTab .= '<div id="transcription-'.$i.'" class="collapse transcription-history-collapse-content">';
                        $editorTab .= '<p id="item-page-current-transcription">';
                            $editorTab .= $transcription['Text'];
                        $editorTab .= '</p>';
                        $editorTab .= '<input class="transcription-comparison-button" type="button" 
                                            onClick="compareTranscription(\''.$transcriptionList[0]['Text'].'\', \''.$currentTranscription['Text'].'\','.$i.')" 
                                            value="Compare to current transcription">';
                        $editorTab .= '<p id="transcription-comparison-output-'.$i.'" class="transcription-comparison-output">';
                        $editorTab .= '</p>';
                    $editorTab .= '</div>';
                $i++;
            }


        // Image settings tab
        $imageSettingsTab = "";
            $imageSettingsTab .= "<p class='theme-color item-page-section-headline'>ADVANCED IMAGE SETTINGS</p>"; 

        // Info tab
        $infoTab = "";
            $infoTab .= "<h4 class='theme-color item-page-section-headline'>";
                $infoTab .= "Title: ".$data['Title'];
            $infoTab .= "</h4>";
            $infoTab .= "<p class='item-page-property-value'>";
                $infoTab .= $data['Description'];
            $infoTab .= "</p>";

            $infoTab .= "<h5 class='theme-color item-page-property-headline'>";
                $infoTab .= "People";
            $infoTab .= "</h5>";
            $infoTab .= "<p class='item-page-property'>";
                $infoTab .= "<span class='item-page-property-key'>";
                    $infoTab .= "Contributor: ";
                $infoTab .= "</span>";
                $infoTab .= "<span class='item-page-property-value'>";
                    $infoTab .= $data['Contributor'];
                $infoTab .= "</span>";
            $infoTab .= "</p>";
            $infoTab .= "<p class='item-page-property'>";
                $infoTab .= "<span class='item-page-property-key'>";
                    $infoTab .= "Subject: ";
                $infoTab .= "</span>";
                $infoTab .= "<span class='item-page-property-value'>";
                    $infoTab .= $data['StoryPlaceName'];
                $infoTab .= "</span>";
            $infoTab .= "</p>";

            $infoTab .= "<h5 class='theme-color item-page-property-headline'>";
                $infoTab .= "Classifications";
            $infoTab .= "</h5>";
            $infoTab .= "<p class='item-page-property'>";
                $infoTab .= "<span class='item-page-property-key'>";
                    $infoTab .= "Type: ";
                $infoTab .= "</span>";
                $infoTab .= "<span class='item-page-property-value'>";
                    $infoTab .= $data['Title'];
                $infoTab .= "</span>";
            $infoTab .= "</p>";
            $infoTab .= "<p class='item-page-property'>";
                $infoTab .= "<span class='item-page-property-key'>";
                    $infoTab .= "Subject: ";
                $infoTab .= "</span>";
                $infoTab .= "<span class='item-page-property-value'>";
                    $infoTab .= $data['StoryPlaceName'];
                $infoTab .= "</span>";
            $infoTab .= "</p>";

            $infoTab .= "<h5 class='theme-color item-page-property-headline'>";
                $infoTab .= "PROPERTIES";
            $infoTab .= "</h5>";
            $infoTab .= "<p class='item-page-property'>";
                $infoTab .= "<span class='item-page-property-key'>";
                    $infoTab .= "Language: ";
                $infoTab .= "</span>";
                $infoTab .= "<span class='item-page-property-value'>";
                    $infoTab .= $data['Languauges'][0];
                $infoTab .= "</span>";
            $infoTab .= "</p>";
            $infoTab .= "<p class='item-page-property'>";
                $infoTab .= "<span class='item-page-property-key'>";
                    $infoTab .= "Keyword: ";
                $infoTab .= "</span>";
                $infoTab .= "<span class='item-page-property-value'>";
                    $infoTab .= $data['SearchText'];
                $infoTab .= "</span></br>";
            $infoTab .= "</p>";
            $infoTab .= "<p class='item-page-property'>";
                $infoTab .= "<span class='item-page-property-key'>";
                    $infoTab .= "Link: ";
                $infoTab .= "</span>";
                $infoTab .= "<span class='item-page-property-value'>";
                    $infoTab .= $data['Link'];
                $infoTab .= "</span></br>";
            $infoTab .= "</p>";
            $infoTab .= "<p class='item-page-property'>";
                $infoTab .= "<span class='item-page-property-key'>";
                    $infoTab .= "Category: ";
                $infoTab .= "</span>";
                $infoTab .= "<span class='item-page-property-value'>";
                    $infoTab .= $data['Title'];
                $infoTab .= "</span></br>";
            $infoTab .= "</p>";

            // Just filler content for now, to make the size realistic
            $infoTab .= "<p class='theme-color item-page-property-headline'>Time</p>";
            $infoTab .= "<span class='item-page-property-key'>Creation date: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['Timestamp']."</span></br>";
            
            $infoTab .= "<p class='theme-color item-page-property-headline'>Provenanace</p>";
            $infoTab .= "<span class='item-page-property-key'>Source: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['Title']."</span></br>";
            $infoTab .= "<span class='item-page-property-key'>Provenance: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['Title']."</span></br>";
            $infoTab .= "<span class='item-page-property-key'>Identifier: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['Title']."</span></br>";
            $infoTab .= "<span class='item-page-property-key'>Institution: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['Title']."</span></br>";
            $infoTab .= "<span class='item-page-property-key'>Provider: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['Title']."</span></br>";
            $infoTab .= "<span class='item-page-property-key'>Providing country: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['Title']."</span></br>";
            $infoTab .= "<span class='item-page-property-key'>First published in Europeana: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['DateStart']."</span></br>";
            $infoTab .= "<span class='item-page-property-key'>Last updated in Europeana: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['DateEnd']."</span></br>";
            
            $infoTab .= "<p class='theme-color item-page-property-headline'>References and relations</p>";
            $infoTab .= "<span class='item-page-property-key'>Location: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['TranscriptionId']."</span></br>";

            $infoTab .= "<p class='theme-color item-page-property-headline'>Location</p>";
            $infoTab .= "<span class='item-page-property-key'>Dataset: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['PlaceId']."</span></br>";

            $infoTab .= "<p class='theme-color item-page-property-headline'>Entities</p>";
            $infoTab .= "<span class='item-page-property-key'>Concept term: </span>";
            $infoTab .= "<span class='item-page-property-value'>".$data['ImageLink']."</span></br>";

        // Tagging tab
        $taggingTab = "";
            $taggingTab .= "<p>test... tags tab</p>";     

        // Help tab
        $helpTab = "";
            $helpTab .= "<p>test... help tab</p>";          

        // Automatic enrichment tab
        $autoEnrichmentTab = "";
            $autoEnrichmentTab .= "<p>test... automatic enrichment tab</p>"; 
        
        // Comment section
        $commentSection = "";
            $commentSection .= "<h2 class=\"theme-color-background comments-head\">";
                $commentSection .= "Notes and questions";
            $commentSection .= "</h2>";
            $commentSection .= "<div id=\"single-comments-wrapper\">";
                $commentSection .= "<div id=\"comments\" class=\"comments-area\">";
                    $commentSection .= "<div id=\"respond\" class=\"comment-respond\">";
                        $commentSection .= "<h3 id=\"reply-title\" class=\"comment-reply-title\">";	
                            $commentSection .= "Leave a note or a question";
                            $commentSection .= "<small><a rel=\"nofollow\" id=\"cancel-comment-reply-link\" href=\"/en/documents/id-19044/item-223349/#respond\" style=\"display:none;\">";
                                $commentSection .= "Cancel reply";
                            $commentSection .= "</a></small>";	
                        $commentSection .= "</h3>";		
                        $commentSection .= "<form action=\"https://transcribathon.com/wp-comments-post.php\" method=\"post\" id=\"commentform\" class=\"comment-form\">";
                            $commentSection .= "<p class=\"logged-in-as\">";
                                $commentSection .= "<a href=\"https://transcribathon.com/wp-admin/profile.php\" aria-label=\"Logged in as ".wp_get_current_user()->display_name.". Edit your profile.\">";
                                    $commentSection .= "Logged in as ".wp_get_current_user()->display_name."";
                                $commentSection .= "</a>.";
                                $commentSection .= "<a href=\"".wp_logout_url(network_home_url())."\">";
                                    $commentSection .= "Log out?";
                                $commentSection .= "</a>";
                            $commentSection .= "</p>";
                            $commentSection .= "<textarea id=\"comment\" name=\"comment\" aria-required=\"true\">";
                            $commentSection .= "</textarea>";
                            $commentSection .= "<input name=\"wpml_language_code\" type=\"hidden\" value=\"en\" />";
                            $commentSection .= "<p class=\"form-submit\">";
                                $commentSection .= "<input name=\"submit\" type=\"submit\" id=\"submit\" class=\"submit\" value=\"Save note\" />";
                                $commentSection .= "<input type='hidden' name='comment_post_ID' value='296152' id='comment_post_ID' />";
                                $commentSection .= "<input type='hidden' name='comment_parent' id='comment_parent' value='0' />";
                            $commentSection .= "</p>";
                            $commentSection .= "<input type=\"hidden\" id=\"_wp_unfiltered_html_comment_disabled\" name=\"_wp_unfiltered_html_comment_disabled\" value=\"1f491b0ac2\" />";
                            $commentSection .= "<script>
                                                    (function() {
                                                        if(window===window.parent){
                                                            document.getElementById('_wp_unfiltered_html_comment_disabled').name='_wp_unfiltered_html_comment';
                                                        }
                                                    }) ();
                                                </script>";
                        $commentSection .= "</form>";
                    $commentSection .= "</div><!-- #respond -->";
                $commentSection .= "</div><!-- #comments .comments-area -->";
            $commentSection .= "</div>";

        // View switcher button
        $content .= "<button id='item-page-switcher' onclick='switchItemPageView()'>switch</button>";

        // <<< FULL VIEW >>> //

        $content .= "<div id='full-view-container' style='display:block'>";
            // Top image slider 
            $content .= "<div class='item-page-slider'>
                            <div><img src='".$data['ImageLink']."'></div>
                            <div><img src='".$data['ImageLink']."'></div>
                            <div><img src='".$data['ImageLink']."'></div>
                            <div><img src='".$data['ImageLink']."'></div>
                            <div><img src='".$data['ImageLink']."'></div>
                            <div><img src='".$data['ImageLink']."'></div>
                            <div><img src='".$data['ImageLink']."'></div>
                            <div><img src='https://transcribathon.com/wp-content/uploads/document-images/21795.258363.full-150x150.jpg'></div>
                            <div><img src='https://transcribathon.com/wp-content/uploads/document-images/21795.258364.full-150x150.jpg'></div>
                            <div><img src='https://transcribathon.com/wp-content/uploads/document-images/21795.258365.full-150x150.jpg'></div>
                            <div><img src='https://transcribathon.com/wp-content/uploads/document-images/21795.258366.full-150x150.jpg'></div>
                            <div><img src='https://transcribathon.com/wp-content/uploads/document-images/21795.258367.full-150x150.jpg'></div>
                            <div><img src='https://transcribathon.com/wp-content/uploads/document-images/21795.258368.full-150x150.jpg'></div>
                            <div><img src='https://transcribathon.com/wp-content/uploads/document-images/21795.258369.full-150x150.jpg'></div>
                            <div><img src='https://transcribathon.com/wp-content/uploads/document-images/21795.258370.full-150x150.jpg'></div>
                            <div><img src='https://transcribathon.com/wp-content/uploads/document-images/21795.258371.full-150x150.jpg'></div>
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
                                    lazyLoad: 'ondemand',
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
                                    ]
                                });
                            });
                        </script>";
            
            $content .= "<div id='full-view-left'>";
                $content .= $imageViewer;
                $content .= "<div id='full-view-editor'>";
                    $content .= $editorTab;
                $content .= "</div>";
            $content .= "</div>";
            $content .= "<div id='full-view-right'>";
                $content .= "<div id='full-view-tagging'>";
                    $content .= $taggingTab;
                $content .= "</div>";
                $content .= "<hr>";
                $content .= '<div class="panel panel-default">';
                    $content .= '<div class="panel-heading clickable" data-toggle="collapse" href="#info-collapsable">';
                        $content .= '<h4 id="info-collapse-heading" class="theme-color item-page-section-headline panel-title">';  
                            $content .= 'DOCUMENT META DATA';
                        $content .= '</h4>';
                        $content .= '<span class="pull-right">';
                            $content .= '<i class="fa fa-angle-down" style="font-size: 20px;"></i>';
                        $content .= '</span>';
                    $content .= '</div>';
                    $content .= '<div id="info-collapsable" class="panel-body panel-collapse collapse">';
                        $content .= "<div id='full-view-info'>";
                            $content .= $infoTab;
                        $content .= "</div>";
                    $content .= "</div>";
                $content .= "</div>";
                $content .= "<hr>";
                $content .= "<div id='full-view-help'>";
                    $content .= $helpTab;
                $content .= "</div>";
                $content .= "<hr>";
                $content .= "<div id='full-view-autoEnrichment'>";
                    $content .= $autoEnrichmentTab;
                $content .= "</div>";
                $content .= "<hr>";
                $content .= "<div id='full-view-comment'>";
                    $content .= $commentSection;
                $content .= "</div>";
            $content .= "</div>";
        $content .= "</div>";


        // Splitscreen container
        $content .= "<div id='image-view-container' class='panel-container-horizontal' style='display:none'>";
            
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
                        $content .= '<i class="fa fa-pencil tablinks active"
                                        onclick="switchItemTab(event, \'editor-tab\')"></i>';
                            
                        $content .= '<i class="fa fa-sliders tablinks"
                                        onclick="switchItemTab(event, \'settings-tab\')"></i>';

                        $content .= '<i class="fa fa-info-circle tablinks"
                                        onclick="switchItemTab(event, \'info-tab\')"></i>';

                        $content .= '<i class="fa fa-globe tablinks"
                                        onclick="switchItemTab(event, \'tagging-tab\')"></i>';

                        $content .= '<i class="fa fa-question-circle tablinks"
                                        onclick="switchItemTab(event, \'help-tab\')"></i>';

                        $content .= '<i class="fa fa-laptop tablinks"
                                        onclick="switchItemTab(event, \'autoEnrichment-tab\')"></i>';
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
                        // Content will be added here in switchItemPageView function
                    $content .= "</div>";

                    // Image settings tab
                    $content .= "<div id='settings-tab' class='tabcontent' style='display:none;'>";
                        $content .= $imageSettingsTab;
                    $content .= "</div>";    

                    // Info tab
                    $content .= "<div id='info-tab' class='tabcontent' style='display:none;'>";
                        $content .= "<p class='theme-color item-page-section-headline'>DOCUMENT META DATA</p>";
                        // Content will be added here in switchItemPageView function
                    $content .= "</div>";

                    // Tagging tab
                    $content .= "<div id='tagging-tab' class='tabcontent' style='display:none;'>";
                        // Content will be added here in switchItemPageView function
                    $content .= "</div>";

                    // Help tab
                    $content .= "<div id='help-tab' class='tabcontent' style='display:none;'>";
                        // Content will be added here in switchItemPageView function
                    $content .= "</div>";

                    // Automatic enrichment tab
                    $content .= "<div id='autoEnrichment-tab' class='tabcontent' style='display:none;'>";
                        // Content will be added here in switchItemPageView function
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

        $content .= "</div> 
                </div>";
        echo $content;
    }
}
add_shortcode( 'item_page_test', '_TCT_item_page_test' );
?>