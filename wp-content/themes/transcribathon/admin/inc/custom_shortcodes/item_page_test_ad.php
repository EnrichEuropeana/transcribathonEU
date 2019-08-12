<?php
/* 
Shortcode: item_page_test_ad
Description: Gets item data and builds the item page
*/

// include required files
include($_SERVER["DOCUMENT_ROOT"].'/wp-load.php');

function _TCT_item_page_test_ad( $atts ) {  
    global $ultimatemember;
    if (isset($_GET['item']) && $_GET['item'] != "") {
        // Set request parameters for image data
        $requestData = array(
            'key' => 'testKey'
        );
        $url = network_home_url()."/tp-api/items/".$_GET['item'];
        $requestType = "GET";
        $isLoggedIn = is_user_logged_in();

        // Execude http request
        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

        // Save image data
        $itemData = json_decode($result, true);
        $itemData = $itemData[0];

        // Set request parameters for story data
        $url = network_home_url()."/tp-api/stories/".$itemData['StoryId'];
        $requestType = "GET";

        // Execude http request
        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

        // Save story data
        $storyData = json_decode($result, true);
        $storyData = $storyData[0];
        //include theme directory for text hovering
        $theme_sets = get_theme_mods();

        // Build Item page content
        $content = "";
        $content = "<style>
                        .transcription-toggle>a:hover {
                            color: ".$theme_sets['vantage_general_link_color']." !important;
                        }

                        #transcription-selected-languages.language-selected ul li {
                            background: ".$theme_sets['vantage_general_link_color']." ;
                        }
                                                
                        .item-page-slider button.slick-prev.slick-arrow:hover {
                            background: ".$theme_sets['vantage_general_link_color']." ;
                            color: #ffffff;
                        }
                        
                        .item-page-slider button.slick-next.slick-arrow:hover {
                            background: ".$theme_sets['vantage_general_link_color']." ;
                            color: #ffffff;
                        }
                    </style>";

        $content .= '<script>
                        window.onclick = function(event) {
                            if (event.target.id != "transcription-status-indicator") {
                                var statusDropdown = document.getElementById("transcription-status-dropdown");
                                if (statusDropdown.classList.contains("show")) {
                                    statusDropdown.classList.remove("show");
                                }
                            }
                            if (event.target.id != "description-status-indicator") {
                                var statusDropdown = document.getElementById("description-status-dropdown");
                                if (statusDropdown.classList.contains("show")) {
                                    statusDropdown.classList.remove("show");
                                }
                            }
                            if (event.target.id != "location-status-indicator") {
                                var statusDropdown = document.getElementById("location-status-dropdown");
                                if (statusDropdown.classList.contains("show")) {
                                    statusDropdown.classList.remove("show");
                                }
                            }
                            if (event.target.id != "tagging-status-indicator") {
                                var statusDropdown = document.getElementById("tagging-status-dropdown");
                                if (statusDropdown.classList.contains("show")) {
                                    statusDropdown.classList.remove("show");
                                }
                            }
                        }
                    </script>';
        $content .= "<script>
                        jQuery ( document ).ready(function() {
                            // When the user clicks the button, open the modal 
                            jQuery('#lock-login').click(function() {
                              jQuery('#item-page-login-container').css('display', 'block');
                            })
                            jQuery('#lock-loginFS').click(function() {
                              jQuery('#item-page-login-container').css('display', 'block');
                            })

                            // When the user clicks on <span> (x), close the modal
                            jQuery('.close').click(function() {
                              jQuery('#item-page-login-container').css('display', 'none');
                            })
                        });
                    </script>";
                    
        $content .= '<div id="item-page-login-container">';
            $content .=   '<div id="item-page-login-modal">';
                $content .=   '<div class="modal-header theme-color-background">';
                    $content .=      '<span class="close">&times;</span>';
                $content .=  '</div>';
                $content .=  '<div class="modal-body">';
                    $content .= do_shortcode('[ultimatemember form_id="40"]');
                $content .= '</div>';
                $content .= '<div class="modal-footer theme-color-background">';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';

        // Image viewer
        $imageViewer = "";
            $imageViewer .= '<div id="openseadragon">  <div class="buttons" id="buttons">';
            $imageViewer .= '<div id="zoom-in"><i class="far fa-plus"></i></div>';
            $imageViewer .= '<div id="zoom-out"><i class="far fa-minus"></i></div>';
            $imageViewer .= '<div id="home"><i class="far fa-home"></i></div>';
            $imageViewer .= '<div id="full-width"><i class="far fa-arrows-alt-h"></i></div>';
            $imageViewer .= '<div id="rotate-right"><i class="far fa-redo"></i></div>';
            $imageViewer .= '<div id="rotate-left"><i class="far fa-undo"></i></div>';
            $imageViewer .= '<div id="filterButton"><i class="far fa-sliders-h"></i></div>';
            $imageViewer .= '<div id="full-page"><i class="far fa-expand-arrows-alt"></i></div>';
            if($isLoggedIn) {
              $imageViewer .= '<div id="transcribe"><i class="far fa-pen"></i></div>';
            } else {
              $imageViewer .= '<div id="transcribe locked"><i class="far fa-lock" id="lock-login"></i></div>';
            }
            $imageViewer .= '</div></div>';
                       
        // Editor tab
        $editorTab = "";
            // Set request parameters for status data
            $url = network_home_url()."/tp-api/completionStatus";
            $requestType = "GET";

            // Execude http request
            include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

            // Save status data
            $statusTypes = json_decode($result, true);

            $progressData = array(
                $itemData['TranscriptionStatusName'],
                $itemData['DescriptionStatusName'],
                $itemData['LocationStatusName'],
                $itemData['TaggingStatusName'],
                $itemData['AutomaticEnrichmentStatusName'],
            );
            $progressCount = array (
                            'Not Started' => 0,
                            'Edit' => 0,
                            'Review' => 0,
                            'Completed' => 0
                        );
            foreach ($progressData as $status) {
                $progressCount[$status] += 1;
            }

            $editorTab .= '<div id="item-progress-section">';
                $editorTab .= '<div id="item-progress-bar" class="item-status-chart">';
                    $editorTab .= '<div id="item-status-bar-info-box" class="item-status-info-box">';
                        $editorTab .= '<ul class="item-status-info-box-list">';
                            foreach ($statusTypes as $statusType) {
                                $percentage = ($progressCount[$statusType['Name']] / sizeof($progressData)) * 100;
                                $editorTab .= '<li>';
                                    $editorTab .= '<span class="status-info-box-color-indicator" style="background-color:'.$statusType['ColorCode'].'; 
                                                    background-image: -webkit-gradient(linear, left top, left bottom, 
                                                    color-stop(0, '.$statusType['ColorCode'].'), color-stop(1, '.$statusType['ColorCodeGradient'].'));">';
                                    $editorTab .= '</span>';
                                    $editorTab .= '<span id="progress-bar-overlay-'.str_replace(' ', '-', $statusType['Name']).'-section" class="status-info-box-percentage" style="width: 20%;">';
                                        $editorTab .= $percentage.'%';
                                    $editorTab .= '</span>';
                                    $editorTab .= '<span class="status-info-box-text">';
                                        $editorTab .= $statusType['Name'];
                                    $editorTab .= '</span>';
                                $editorTab .= '</li>';
                            }
                        $editorTab .= '</ul>';
                    $editorTab .= '</div>';
                    foreach ($statusTypes as $statusType) {
                        $percentage = ($progressCount[$statusType['Name']] / sizeof($progressData)) * 100;
                        if ($percentage != 0) {
                            switch ($statusType['Name']) {
                                case "Completed":
                                    $CompletedBar .= '<div id="progress-bar-'.str_replace(' ', '-', $statusType['Name']).'-section" class="progress-bar progress-bar-section"
                                                        style="width: '.$percentage.'%; background-color:'.$statusType['ColorCode'].';
                                                        ">';
                                        $CompletedBar .= $percentage.'%';
                                    $CompletedBar .= '</div>';
                                    break;
                                case "Review":
                                    $ReviewBar .= '<div id="progress-bar-'.str_replace(' ', '-', $statusType['Name']).'-section" class="progress-bar progress-bar-section"
                                                        style="width: '.$percentage.'%; background-color:'.$statusType['ColorCode'].'">';
                                        $ReviewBar .= $percentage.'%';
                                    $ReviewBar .= '</div>';
                                    break;
                                case "Edit":
                                    $EditBar .= '<div id="progress-bar-'.str_replace(' ', '-', $statusType['Name']).'-section" class="progress-bar progress-bar-section"
                                                        style="width: '.$percentage.'%; background-color:'.$statusType['ColorCode'].'">';
                                        $EditBar .= $percentage.'%';
                                    $EditBar .= '</div>';
                                    break;
                                case "Not Started":
                                    $NotStartedBar .= '<div id="progress-bar-'.str_replace(' ', '-', $statusType['Name']).'-section" class="progress-bar progress-bar-section"
                                                        style="width: '.$percentage.'%; background-color:'.$statusType['ColorCode'].'">';
                                        $NotStartedBar .= $percentage.'%';
                                    $NotStartedBar .= '</div>';
                                    break;
                            }

                        }
                        else {
                            switch ($statusType['Name']) {
                                case "Completed":
                                    $CompletedBar .= '<div id="progress-bar-'.str_replace(' ', '-', $statusType['Name']).'-section" class="progress-bar progress-bar-section"
                                                        style="width: '.$percentage.'%; background-color:'.$statusType['ColorCode'].'">';
                                        $CompletedBar .= $percentage.'%';
                                    $CompletedBar .= '</div>';
                                    break;
                                case "Review":
                                    $ReviewBar .= '<div id="progress-bar-'.str_replace(' ', '-', $statusType['Name']).'-section" class="progress-bar progress-bar-section"
                                                        style="width: '.$percentage.'%; background-color:'.$statusType['ColorCode'].'">';
                                        $ReviewBar .= $percentage.'%';
                                    $ReviewBar .= '</div>';
                                    break;
                                case "Edit":
                                    $EditBar .= '<div id="progress-bar-'.str_replace(' ', '-', $statusType['Name']).'-section" class="progress-bar progress-bar-section"
                                                        style="width: '.$percentage.'%; background-color:'.$statusType['ColorCode'].'">';
                                        $EditBar .= $percentage.'%';
                                    $EditBar .= '</div>';
                                    break;
                                case "Not Started":
                                    $NotStartedBar .= '<div id="progress-bar-'.str_replace(' ', '-', $statusType['Name']).'-section" class="progress-bar progress-bar-section"
                                                        style="width: '.$percentage.'%; background-color:'.$statusType['ColorCode'].'">';
                                        $NotStartedBar .= $percentage.'%';
                                    $NotStartedBar .= '</div>';
                                    break;
                            }
                        }
                    }
                        $editorTab .= $CompletedBar;
                        $editorTab .= $ReviewBar;
                        $editorTab .= $EditBar;
                        $editorTab .= $NotStartedBar;
                    $editorTab .= '</div>';
                /*$editorTab .= '<div class="prog-refer">';
                    $editorTab .= '<ul>';
                        foreach ($statusTypes as $statusType) {
                            $editorTab .= '<li><span class="colorbox" style="background-color: '.$statusType['ColorCode'].';"></span> '.$statusType['Name'].'</li>';
                        }
                    $editorTab .= '</ul>';
                $editorTab .= '</div>'; */
            $editorTab .= '</div>';


           // Current transcription
           $editorTab .= "<div id='transcription-section' class='item-page-section'>";
           $editorTab .= "<div class='item-page-section-headline-container'>";
               $editorTab .= "<h4 class='theme-color item-page-section-headline'>";
                   $editorTab .= "TRANSCRIPTION";
               $editorTab .= "</h4>";
               //$editorTab .= do_shortcode('[ultimatemember form_id="38"]');
               //status-changer
               $editorTab .= "<div class='item-page-section-headline-right-site'>";
                   $editorTab .= '<div id="transcription-status-changer" class="status-changer section-status-changer">';
                       $editorTab .= '<i id="transcription-status-indicator" class="fal fa-circle status-indicator"
                                           style="color: '.$itemData['TranscriptionStatusColorCode'].'; background-color:'.$itemData['TranscriptionStatusColorCode'].';"
                                           onclick="document.getElementById(\'transcription-status-dropdown\').classList.toggle(\'show\')"></i>';
                       $editorTab .= '<div id="transcription-status-dropdown" class="sub-status status-dropdown-content">';

                           foreach ($statusTypes as $statusType) {
                               if ($itemData['TranscriptionStatusId'] == $statusType['CompletionStatusId']) {
                                   $editorTab .= "<div class='status-dropdown-option status-dropdown-option-current'
                                                       onclick=\"changeStatus(".$_GET['item'].",'".$statusType['Name']."', 'TranscriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                   $editorTab .= "<i class='fal fa-circle' style='color: transparent;
                                                       background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'>
                                                   </i>".$statusType['Name']."</div>";
                               } else {
                                   $editorTab .= "<div class='status-dropdown-option'
                                                       onclick=\"changeStatus(".$_GET['item'].", '".$statusType['Name']."', 'TranscriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                   $editorTab .= "<i class='fal fa-circle' style='color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                               }
                           }
                       $editorTab .= '</div>';
                   $editorTab .= '</div>';
               $editorTab .= '</div>';
           $editorTab .= '</div>';
           $editorTab .= '<div style="clear: both;"></div>';

           $currentTranscription = "";
           $transcriptionList = [];
           foreach ($itemData["Transcriptions"] as $transcription) {
               if ($transcription['CurrentVersion'] == "1") {
                   $currentTranscription = $transcription['Text'];
               }
               else {
                   array_push($transcriptionList, $transcription);
               }
           }
           $editorTab .= '<div id="mce-wrapper-transcription">';
               $editorTab .= '<div id="mytoolbar-transcription"></div>';
               $editorTab .= '<div id="item-page-transcription-text" rows="4">';
               $editorTab .= $currentTranscription;
               $editorTab .= '</div>';
           $editorTab .= '</div>';

               $editorTab .= '<div id="transcription-language-selector">';
                           // Set request parameters for language data
                       $url = network_home_url()."/tp-api/languages";
                       $requestType = "GET";

                           // Execude http request
                       include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                           // Save language data
                      // Save language data
                      $languages = json_decode($result, true);

                      // Set request parameters for item language data
                  $url = network_home_url()."/tp-api/transcriptionLanguages?ItemId=".$_GET['item'];
                  $requestType = "GET";

                      // Execude http request
                  include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                      // Save language data
                  
                  $transcriptionLanguages = $transcriptionData[0]['Languages'];

                  $editorTab .= '<select style="padding: 4px; outline:none;">';
                      $editorTab .= '<option value="" disabled selected hidden>';
                          $editorTab .= 'Please select a language...';
                      $editorTab .= '</option>';
                      foreach ($languages as $language) {
                          $editorTab .= '<option value="'.$language['LanguageId'].'">';
                              $editorTab .= $language['Name'];
                          $editorTab .= '</option>';
                      }
                  $editorTab .= '</select>';
              $editorTab .= '</div>';
              $editorTab .= '<div id="transcription-selected-languages" class="language-selected">';
                  $editorTab .= '<ul>';
                      if ($transcriptionData[0]['Languages'] != null) {
                          $transcriptionLanguages = $transcriptionData[0]['Languages'];
                                  foreach($transcriptionLanguages as $transcriptionLanguage) {
                                      $editorTab .= "<li onClick='removeTranscriptionLanguage(".$transcriptionLanguage['LanguageId'].", this)'>";
                                          $editorTab .= $transcriptionLanguage['Name'];
                                          $editorTab .= '<script>
                                                              jQuery("#transcription-language-selector option[value=\''.$transcriptionLanguage['LanguageId'].'\'").prop("disabled", true)
                                                          </script>';
                                      $editorTab .= '</li>';
                                  }
                      }
                  $editorTab .= '</ul>';
              $editorTab .= '</div>';

              $editorTab .= '<div class="transcription-metadata-container">';
               $editorTab .= '<div id="no-text-selector">';
                   $editorTab .= '<label class="square-checkbox-container">';
                       $editorTab .= '<span>No Text</span>';
                       $editorTab .= '<input id="type-'.$category['PropertyId'].'-checkbox" type="checkbox" '.$checked.' 
                                           name="'.$category['PropertyValue'].'"value="'.$category['PropertyId'].'">';
                       $editorTab .= '<span class="theme-color-background checkmark"></span>';
                   $editorTab .= '</label>';
               $editorTab .= '</div>';

                $editorTab .= "<button class='transcription-save-button theme-color-background' id='transcription-update-button' onClick='updateItemTranscription(".$itemData['ItemId'].", ".get_current_user_id().")'>";
                    $editorTab .= "SAVE"; // save transcription
                $editorTab .= "</button>";
                $editorTab .= "<div style='clear:both'></div>";
                    $editorTab .= "<span id='transcription-update-message'></span>";
            $editorTab .= '</div>';
       $editorTab .= '</div>';

            // Description
            $editorTab .= '<div class="item-page-section">';
                $editorTab .= '<div class="item-page-section-headline-container collapse-headline  item-page-section-collapse-headline collapse-controller" data-toggle="collapse" href="#description-area"
                                    onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')
                                    jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')">';
                    $editorTab .= '<h4 id="description-collapse-heading" class="theme-color item-page-section-headline">';
                        $editorTab .= "DESCRIPTION";
                    $editorTab .= '</h4>';
                    $editorTab .= '<i class="far fa-caret-circle-up collapse-icon theme-color" style="font-size: 17px; float:left; margin-right: 8px; margin-top: 9px;"></i>';
                $editorTab .= '</div>';
                //status-changer
                $editorTab .= '<div id="description-status-changer" class="status-changer section-status-changer">';
                    $editorTab .= '<i id="description-status-indicator" class="fal fa-circle status-indicator"
                                        style="color: '.$itemData['DescriptionStatusColorCode'].'; background-color:'.$itemData['DescriptionStatusColorCode'].';"
                                        onclick="document.getElementById(\'description-status-dropdown\').classList.toggle(\'show\')"></i>';
                    $editorTab .= '<div id="description-status-dropdown" class="sub-status status-dropdown-content">';
                        foreach ($statusTypes as $statusType) {
                            if ($itemData['DescriptionStatusId'] == $statusType['CompletionStatusId']) {
                                $editorTab .= "<div class='status-dropdown-option status-dropdown-option-current'
                                                    onclick=\"changeStatus(".$_GET['item'].", '".$statusType['Name']."', 'DescriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                $editorTab .= "<i class='fal fa-circle' style='color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                            } else {
                                $editorTab .= "<div class='status-dropdown-option'
                                                    onclick=\"changeStatus(".$_GET['item'].", '".$statusType['Name']."', 'DescriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                $editorTab .= "<i class='fal fa-circle' style='color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                            }
                        }
                    $editorTab .= '</div>';
                $editorTab .= '</div>';
                $editorTab .= '<div style="clear: both;"></div>';
                    $editorTab .= "<div id=\"description-area\" class=\"description-save transcription-history-area collapse show\">";
                        $editorTab .= "<div id=\"category-checkboxes\">";
                            // Set request parameters for category data
                            $url = network_home_url()."/tp-api/properties?PropertyType=Category";
                            $requestType = "GET";

                            // Execude http request
                            include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                            // Save category data
                            $categories = json_decode($result, true);

                            foreach ($categories as $category) {
                                $checked = "";
                                foreach ($itemData['Properties'] as $itemProperty) {
                                    if ($itemProperty['PropertyId'] == $category['PropertyId']) {
                                        $checked = "checked";
                                        break;
                                    }
                                } 
                                $editorTab .= '<label class="square-checkbox-container">';
                                        $editorTab .= $category['PropertyValue'];
                                        $editorTab .= '<input class="category-checkbox" id="type-'.$category['PropertyValue'].'-checkbox" type="checkbox" '.$checked.'
                                                            name="'.$category['PropertyValue'].'"value="'.$category['PropertyId'].'"
                                                            onClick="addItemProperty('.$_GET['item'].', this)">';
                                        $editorTab .= '<span  class="theme-color-background checkmark"></span>';
                                    $editorTab .= '</label>';
                                }
                                $editorTab .= '<div style="clear: both;"></div>';
                        $editorTab .= '</div>';

                        $editorTab .= '<textarea id="item-page-description-text" rows="4">';
                            if ($itemData['Description'] != null) {
                                $editorTab .= $itemData['Description'];
                            }
                        $editorTab .= '</textarea>';

                    // Set request parameters for language data
                    $url = network_home_url()."/tp-api/languages";
                    $requestType = "GET";

                    // Execude http request
                    include dirname(__FILE__)."/../custom_scripts/send_api_request.php";
                    // Save language data
                    $languages = json_decode($result, true);
                    $editorTab .= '<div id= "description-language-selector">';
                        $editorTab .= '<select style="padding: 4px; outline:none;" name="" id="" title="" class="">';
                            $editorTab .= '<option value="" disabled selected hidden>';
                                $editorTab .= 'Please select a language...';
                            $editorTab .= '</option>';
                            foreach ($languages as $language) {
                                $editorTab .= '<option value="'.$language['ShortName'].'">';
                                    $editorTab .= $language['Name'];
                                $editorTab .= '</option>';
                            }
                        $editorTab .= '</select>';
                    $editorTab .= '</div>';
                        $editorTab .= "<button class='theme-color-background' id='description-update-button' style='float: right;' onClick='updateItemDescription(".$itemData['ItemId'].")'>";
                            $editorTab .= "SAVE"; //save description
                        $editorTab .= "</button>";
                        $editorTab .= "<script>                                      
                                            jQuery('.description-save textarea').keyup(function() {
                                            var block_data = jQuery(this).val();
                                                    if(block_data.length==0){
                                                    jQuery('#description-update-button').css('display','none');
                                                    }else{
                                                jQuery('#description-update-button').css('display','block');
                                                }
                                            });
                                        </script>";
                        $editorTab .= "<div style='clear:both'></div>";
                        $editorTab .= "<span id='description-update-message'></span>";
                    $editorTab .= '</div>';
                $editorTab .= '</div>';

                // Transcription history
            $editorTab .= '<div class="item-page-section">';
                $editorTab .= '<div class="item-page-section-headline-container collapse-headline item-page-section-collapse-headline collapse-controller" data-toggle="collapse" href="#transcription-history"
                                            onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')
                                            jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')">';
                    $editorTab .= '<h4 id="transcription-history-collapse-heading" class="theme-color item-page-section-headline">';
                        $editorTab .= "TRANSCRIPTION HISTORY";
                    $editorTab .= '</h4>';
                    $editorTab .= '<i class="far fa-caret-circle-down collapse-icon theme-color" style="font-size: 17px; float:left; margin-right: 8px; margin-top: 9px;"></i>';
                $editorTab .= '</div>';
                $editorTab .= '<div style="clear: both;"></div>';
                $editorTab .= "<div id=\"transcription-history\" class=\"collapse\">";
                $i = 0;
                foreach ($transcriptionList as $transcription) {
                    $editorTab .= '<div class="transcription-toggle" data-toggle="collapse" data-target="#transcription-'.$i.'">';
                        $editorTab .='<i class="fas fa-calendar-day" style= "margin-right: 6px;"></i>';
                        $date = strtotime($transcription["Timestamp"]);
                        $editorTab .= '<span class="day-n-time">';
                            $editorTab .= $transcription["Timestamp"];
                        $editorTab .= '</span>';
                        $editorTab .= '<i class="fas fa-user-alt" style= "margin: 0 6px;"></i>';
                        $editorTab .= '<span class="day-n-time">';
                            $editorTab .= '<a href="#">';
                                $editorTab .= ' USER-NAME';
                            $editorTab .= '</a>';
                        $editorTab .= '</span>';
                        $editorTab .= '<i class="fas fa-angle-down" style= "float:right;"></i>';
                        //$content .= "<dd>".$custom['tct_story_id'][0]."</dd>\n";

                        //<span class="usr"><a href="/en/user/red-mini/">Red-Mini </a></span>
                                // $editorTab .= $transcription["Timestamp"];
                    $editorTab .= '</div>';
                    $editorTab .= '<div id="transcription-'.$i.'" class="collapse transcription-history-collapse-content">';
                        $editorTab .= '<p id="item-page-current-transcription">';
                            $editorTab .= $transcription['Text'];
                        $editorTab .= '</p>';
                        $editorTab .= '<input class="transcription-comparison-button" type="button"
                                            onClick="compareTranscription(\''.$transcriptionList[$i]['Text'].'\', \''.$currentTranscription.'\','.$i.')"
                                            value="Compare to current transcription">';
                        $editorTab .= '<p id="transcription-comparison-output-'.$i.'" class="transcription-comparison-output"></p>';
                    $editorTab .= '</div>';
                    $i++;
                }
                $editorTab .= '</div>';
            $editorTab .= '</div>';



        // Image settings tab
        $imageSettingsTab = "";
            $imageSettingsTab .= "<p class='theme-color item-page-section-headline'>ADVANCED IMAGE SETTINGS</p>";

        // Info tab
        $infoTab = "";
        $infoTab .= '<div class="item-page-section additional-info-bottom">';
            $infoTab .= '<div id="info-collapse-headline-container" class="item-page-section-headline-container collapse-headline collapse-controller" data-toggle="collapse" href="#additional-information-area"
                            onClick="">';
                $infoTab .= '<h4 id="info-collapse-heading" class="theme-color item-page-section-headline">';
                    $infoTab .= 'Additional Information';
                $infoTab .= '</h4>';
                $infoTab .= '<i class="fal fa-info-square theme-color" style="font-size: 17px; float:left;  margin-right: 8px; margin-top: 9.6px;"></i>';
            $infoTab .= '</div>';

            $infoTab .= '<div id="additional-information-area">';
                $infoTab .= "<h4 class='theme-color item-page-section-headline'>";
                    $infoTab .= "Title: ".$itemData['Title'];
                $infoTab .= "</h4>";
                $infoTab .= "<p class='item-page-property-value'>";
                    $infoTab .= $itemData['Description'];
                $infoTab .= "</p>";

                // Set request parameters
                $url = network_home_url()."/tp-api/fieldMappings";
                $requestType = "GET";

                // Execude request
                include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                // Display data
                $fieldMappings = json_decode($result, true);

                $fields = array();
                foreach ($fieldMappings as $fieldMapping) {
                    $fields[$fieldMapping['Name']] = $fieldMapping['DisplayName'];
                }
                foreach ($storyData as $key => $value) {
                    if ($fields[$key] != null && $fields[$key] != "") {
                        $infoTab .= "<p class='item-page-property'>";
                            $infoTab .= "<span class='item-page-property-key'>";
                                $infoTab .= $fields[$key].": ";
                            $infoTab .= "</span>";
                            $infoTab .= "<span class='item-page-property-value'>";
                                if (filter_var($value, FILTER_VALIDATE_URL)) {
                                    $infoTab .= "<a href=\"".$value."\">".$value."</a>";
                                }
                                else {
                                    $infoTab .= $value;
                                }
                                
                            $infoTab .= "</span></br>";
                        $infoTab .= "</p>";
                    }
                }
            $infoTab .= "</div>";
        $infoTab .= "</div>";

        // Tagging tab
        $taggingTab = "";
                $taggingTab .= "<div class='geo-tagging-container'>";
                    $taggingTab .= "<div id='geo-tagging-map' class='geo-map-container'>";
                        $taggingTab .= '<iframe src="https://www.google.com/maps/embed?pb=" width="800" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>';
                            $taggingTab .= '<div id="geo-location-button" class= "collapse-headline collapse-controller" data-toggle="collapse" href="#modalocation" onClick="">';
                            // Trigger/Open The Modal
                                $taggingTab .= '<i class="fal fa-map-marker-plus theme-color" style="font-size:30px;"></i>';
                                $taggingTab .= '<span class= "geoonhover-indicator">Click to add location</span>';
                            $taggingTab .= '</div>';
                    $taggingTab .= "</div>";
                    $taggingTab .= '<div class="location-inputs-container">';
                        
                            // The Modal 
                                $taggingTab .= '<div class="modalocation-content item-map-modal collapse" id="modalocation">';
                                    $taggingTab .= '<form id= "add-location-form" action="" method="post">';
                                    
                                        $taggingTab .= '<div class="location-common location-detail-intro-line">';
                                            $taggingTab .= "<p>Add main location to</p>";
                                            /*$taggingTab .= '<span class="close">&times;</span>';*/
                                        $taggingTab .= '</div>';

                                        $taggingTab .= '<div class="location-common">';
                                            $taggingTab .= '<div id="location-input-section-top">';
                                                $taggingTab .= '<div id="location-name-container" class="location-input-container">';
                                                    $taggingTab .= '<label>Location name:</label><br/>';
                                                    $taggingTab .= '<input id="display-location-test" type="text" name="" placeholder="">';
                                                $taggingTab .= '</div>';
                                                $taggingTab .= '<div id="location-coordinates-container" class="location-input-container">';
                                                    $taggingTab .=    '<label>Coordinates:</label><br/>';
                                                    $taggingTab .=    '<input type="text" name="" placeholder="">';
                                                $taggingTab .= '</div>';
                                                $taggingTab .= "<div style='clear:both;'></div>";
                                            $taggingTab .= '</div>';
                                        $taggingTab .= '</div>';

                                        $taggingTab .= '<div id="location-description-container" class="location-input-container">';
                                            $taggingTab .= '<label>Description (enter here):</label><br/>';
                                            $taggingTab .= '<textarea id="saving-description-test" rows= "2" style="resize:none;" class="gsearch-form" type="text" id="ldsc" placeholder="" name=""></textarea>';
                                        $taggingTab .= '</div>';

                                        $taggingTab .= '<div id="location-geonames-search-container" class="location-input-container location-search-container">';
                                            $taggingTab .= '<label>Search Geonames (enter address):</label><br/>';
                                            $taggingTab .= '<input type="text" id="lgns" placeholder="" name="">';
                                            $taggingTab .= '<a id="geonames-search-button" href="">';
                                                $taggingTab .= '<i class="far fa-search"></i>';
                                            $taggingTab .= '</a>';
                                        $taggingTab .= '</div>';

                                        $taggingTab .= '<div id="location-google-search-container" class="">';
                                            $taggingTab .= '<label></label><br/>';
                                            $taggingTab .= '<input type="text" id="lgs" placeholder="" name="">';
                                            $taggingTab .= '<a id="google-search-button" href="" theme-color-background">';
                                                $taggingTab .= '<i class="far fa-search"></i>';
                                            $taggingTab .= '</a>';
                                        $taggingTab .= '</div>';

                                        $taggingTab .= "<button type='submit' class='save-location theme-color-background' id='location-update-button' onClick='updateItemTranscription(".$itemData['ItemId'].", ".get_current_user_id().")'>";
                                                $taggingTab .= "SAVE";
                                                $taggingTab .= '<script>
                                                                function onButtonClick(){
                                                                    document.getElementById("textInput").className="show";
                                                                }
                                                            </script>';
                                        $taggingTab .= "</button>";

                                        $taggingTab .= "<div style='clear:both;'></div>";
                                        
                                    $taggingTab .= '</form>';
                                    
                                $taggingTab .= "</div>";
                                $taggingTab .= '<div id="saved-location-demo">
                                                    <ul></ul>
                                                </div>';
                                
                                            $taggingTab .= '<script>
                                                                jQuery(document).ready(function(){
                                                                    var a=" ";
                                                                    var b=" ";
                                                                    jQuery("#add-location-form").submit(function(){
                                                                        var a= jQuery("#display-location-test").val();
                                                                        var b= "<li><span>Story Location</span><h4>"+a+"</h4></li>";                                                                            
                                                                        jQuery("#saved-location-demo ul").append(b);

                                                                        return false;
                                                                    });
                                                                });
                                                            </script>';
                    $taggingTab .= "</div>";
                    $taggingTab .= "<div style='clear:both;'></div>";
                $taggingTab .= "</div>";
                $taggingTab .= "<div class='item-page-section-headline-container'>";
                            // Location section
                    $taggingTab .= "<div id='location-section' class='item-page-section'>";
                        $taggingTab .= "<i class='fal fa-map-marker-alt theme-color' style='padding-right: 3px; font-size: 17px; margin-right:8px;'></i>";
                        $taggingTab .= "<h4 class='theme-color item-page-section-headline'>";
                            $taggingTab .= "Locations";
                        $taggingTab .= "</h4>";
                            //status-changer
                        $taggingTab .= "<div class='item-page-section-headline-right-site'>";
                            $taggingTab .= '<div id="location-status-changer" class="status-changer section-status-changer">';
                                $taggingTab .= '<i id="location-status-indicator" class="fal fa-circle status-indicator"
                                                    style="color: '.$itemData['LocationStatusColorCode'].'; background-color:'.$itemData['LocationStatusColorCode'].';"
                                                    onclick="document.getElementById(\'location-status-dropdown\').classList.toggle(\'show\')"></i>';
                                $taggingTab .= '<div id="location-status-dropdown" class="sub-status status-dropdown-content">';
                                    foreach ($statusTypes as $statusType) {
                                        if ($itemData['LocationStatusId'] == $statusType['CompletionStatusId']) {
                                            $taggingTab .= "<div class='status-dropdown-option status-dropdown-option-current'
                                                                onclick=\"changeStatus(".$_GET['item'].",  '".$statusType['Name']."', 'LocationStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                            $taggingTab .= "<i class='fal fa-circle' style='color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                                        } else {
                                            $taggingTab .= "<div class='status-dropdown-option'
                                                                onclick=\"changeStatus(".$_GET['item'].",  '".$statusType['Name']."', 'LocationStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                            $taggingTab .= "<i class='fal fa-circle' style='color: transparent;background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                                        }
                                    }
                                $taggingTab .= '</div>';
                            $taggingTab .= '</div>';
                        $taggingTab .= '</div>';
                    $taggingTab .= "</div>";

                $taggingTab .= '</div>';
                $taggingTab .= '<hr>';                                 

            //Tagging section
            $taggingTab .= "<div id='tagging-section' class='item-page-section'>";
                $taggingTab .= "<div class='item-page-section-headline-container'>";
                    $taggingTab .= "<i class='fal fa-tag theme-color' style='font-size: 17px; margin-right:8px;'></i><h4 class='theme-color item-page-section-headline'>";
                        $taggingTab .= "Tagging";
                    $taggingTab .= "</h4>";
                        //status-changer
                    $taggingTab .= "<div class='item-page-section-headline-right-site'>";
                        $taggingTab .= '<div id="tagging-status-changer" class="status-changer section-status-changer">';
                            $taggingTab .= '<i id="tagging-status-indicator" class="fal fa-circle status-indicator"
                                                style="color: '.$itemData['TaggingStatusColorCode'].'; background-color:'.$itemData['TaggingStatusColorCode'].';"
                                                onclick="document.getElementById(\'tagging-status-dropdown\').classList.toggle(\'show\')"></i>';
                            $taggingTab .= '<div id="tagging-status-dropdown" class="sub-status status-dropdown-content">';
                                foreach ($statusTypes as $statusType) {
                                    if ($itemData['TaggingStatusId'] == $statusType['CompletionStatusId']) {
                                        $taggingTab .= "<div class='status-dropdown-option status-dropdown-option-current'
                                                            onclick=\"changeStatus(".$_GET['item'].",  '".$statusType['Name']."', 'TaggingtatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                        $taggingTab .= "<i class='fal fa-circle' style='color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                                    } else {
                                        $taggingTab .= "<div class='status-dropdown-option'
                                                            onclick=\"changeStatus(".$_GET['item'].",  '".$statusType['Name']."', 'TaggingStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                        $taggingTab .= "<i class='fal fa-circle' style='color: transparent;background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                                    }
                                }
                            $taggingTab .= '</div>';
                        $taggingTab .= '</div>';
                    $taggingTab .= '</div>';
                $taggingTab .= '</div>';

                $taggingTab .= '<div id="item-date-container">';
                    $taggingTab .= '<p>';
                        $taggingTab .= 'Document date';
                    $taggingTab .= '</p>';
                        $taggingTab .= '<div class="item-date-inner-container">';
                            $taggingTab .= '<label>';
                                $taggingTab .= 'Start Date';
                            $taggingTab .= '</label>';
                            if ($itemData['DateStart'] != null) {
                                $startTimestamp = strtotime($itemData['DateStart']);
                                $dateStart = date("d/m/Y", $startTimestamp);
                                $taggingTab .= '<input type="text" id="startdateentry" value="'.$dateStart.'" placeholder="dd/mm/yyyy">';
                            }
                            else {
                                $taggingTab .= '<input type="text" id="startdateentry" placeholder="dd/mm/yyyy">';
                            }
                        $taggingTab .= "</div>";
                        $taggingTab .= '<div class="item-date-inner-container">';
                            $taggingTab .= '<label>';
                                $taggingTab .= 'End Date';
                            $taggingTab .= '</label>';
                            if ($itemData['DateEnd'] != null) {
                                $endTimestamp = strtotime($itemData['DateEnd']);
                                $dateEnd = date("d/m/Y", $endTimestamp);
                                $taggingTab .= '<input type="text" id="enddateentry" value="'.$dateEnd.'" placeholder="dd/mm/yyyy">';
                            }
                            else {
                                $taggingTab .= '<input type="text" id="enddateentry" placeholder="dd/mm/yyyy">';
                            }
                        $taggingTab .= "</div>";
                        $taggingTab .= "<button class='document-date-save-button theme-color-background' id='item-date-save-button' 
                                            onClick='saveItemDate(".$itemData['ItemId'].")'>";
                            $taggingTab .= "SAVE DATE";
                        $taggingTab .= "</button>";
                        $taggingTab .= '<div style="clear:both;"></div>';
                $taggingTab .= '</div>';
                
                $taggingTab .= '<hr>';


                //add person metadata area
                $taggingTab .= '<div class="person-info-area">'; 
                    //add person collapse heading 
                    $taggingTab .= '<div class= "person-info-headline collapse-headline collapse-controller theme-color" data-toggle="collapse" href="#person-data-collapser"
                                        onClick="">';                                          
                        $taggingTab .= '<p>';
                            $taggingTab .= '<span title="Click to add person">';
                                $taggingTab .= 'Add Person data';
                                $taggingTab .= '<i class="fas fa-plus-circle" style="padding-left:6px;"></i>';
                            $taggingTab .= '</span>';
                        $taggingTab .= '</p>';
                    $taggingTab .= '</div>';

                    // add person form area
                    $taggingTab .= '<div class="collapse" id="person-data-collapser">';
                        $taggingTab .= '<form id="add-personinfo-form" action="" method="post">';
                            $taggingTab .= '<div id="person-names-entry-inputs">';
                                $taggingTab .= '<input type="text" id="first-name-entry" class="saving-person-test" name="" placeholder="First Name" style="outline:none;">';
                                $taggingTab .= '<input type="text" id="last-name-entry" class="saving-person-test" name="" placeholder="Last Name" style="outline:none; margin-left: 27px;">';
                            $taggingTab .= '</div>'; 

                            $taggingTab .= '<div id="person-location-birth-inputs">';
                                $taggingTab .= '<input type="text" id="person-birthloc-entry"  class="saving-person-test" name="" placeholder="Birth Location" style="outline:none; margin-right: 27px;">';
                                $taggingTab .= '<input type="text" id="dob-entry" class="saving-person-test person-dob-entry" name="" placeholder="Birth: dd/mm/yyyy">';
                            $taggingTab .= '</div>'; 

                            $taggingTab .= '<div id="person-location-death-inputs">';
                                $taggingTab .= '<input type="text" id="person-deathloc-entry" class="saving-person-test" name="" placeholder="Death Location" style="outline:none; margin-right: 27px;">';
                                $taggingTab .= '<input type="text" id="dod-entry" class="saving-person-test person-dod-entry" name="" placeholder="Death: dd/mm/yyyy">';
                            $taggingTab .= '</div>';    

                            $taggingTab .= '<div id="person-additional-inputs" class="person-description-container">';
                                $taggingTab .= '<label>Additional description:</label><br/>';
                                $taggingTab .= '<textarea class="saving-person-test" rows= "1" style="resize:none; outline:none; width:30em;" class="gsearch-form" type="text" id="ldsc" placeholder="" name=""></textarea>';
                            $taggingTab .= '</div>';

                            $taggingTab .= "<button type='submit' class='save-personinfo-button theme-color-background' id='personinfo-update-button' onClick=''>";
                                                $taggingTab .= "SAVE";

                                                $taggingTab .= '<script>
                                                                function onButtonClick(){
                                                                    document.getElementById("textInput").className="show";
                                                                }
                                                            </script>';

                            $taggingTab .= "</button>";

                            $taggingTab .= '<div style="clear:both;"></div>';
                        $taggingTab .= '</form>';                                         
                    $taggingTab .= '</div>';
                    $taggingTab .= '<div id="display-persondata-demo"></div>';
                                    $taggingTab .= '<script>
                                                        jQuery(document).ready(function(){
                                                            var a=" ";
                                                            var b=" ";
                                                            jQuery("#add-personinfo-form").submit(function(){
                                                                jQuery(".saving-person-test").each(function(){
                                                                a = jQuery(this).val();
                                                                b += "<li>" + a + "</li>";
                                                                });
                                                                jQuery("#display-persondata-demo").append("<ul>"+b+"</ul>");
                                                                b=" ";
                                                                return false;
                                                            });
                                                        });
                                                </script>';
                                               
                $taggingTab .= '</div>';

                $taggingTab .= '<hr>';

                //key word metadata area
                $taggingTab .= '<div class="keyword-entry-area">';
                    //$taggingTab .= '<p><span>Keywords:</span></p>';
                        $taggingTab .= '<div class="keyword-entry-headline collapse-headline collapse-controller" data-toggle="collapse" href="#add-keyword-form"
                            onClick="">';
                            $taggingTab .= '<span id="adding-new-keyword"  title="Click to add keywords" class="theme-color" href="#">';
                                $taggingTab .= 'Keywords';
                                $taggingTab .= '<i class="fas fa-plus-circle" style="padding-left:6px;"></i>';
                            $taggingTab .= '</span>';
                        $taggingTab .= '</div>';

                        $taggingTab .= '<div  id="add-keyword-form" class="collapse">';
                            $taggingTab .= '<form class="keywords-entry-form" action="" method="post">';
                                $taggingTab .= '<input type="text" id="keyword" class="keywords-entry-test" name="" placeholder="" style="outline:none;">';
                                $taggingTab .= '<input id="keyword-save-button" type="submit" value="+" class="theme-color-background add-keyword-button" onclick=""/>';
                            $taggingTab .= '</form>';
                        $taggingTab .= '</div>';

                        $taggingTab .= '<p id="add-keywords-here-demo"></p>';
                        $taggingTab .= '<script>
                                            jQuery(document).ready(function(){
                                                var d=" ";
                                                jQuery("#keywords-entry-form").submit(function(){
                                                    jQuery(".keywords-entry-test").each(function(){
                                                    d += jQuery(this).val()+ "<br/>";
                                                        
                                                    });
                                                    jQuery("#add-keywords-here-demo").html(d);
                                                    return false;
                                                });
                                            });
                                        </script>';
                $taggingTab .= '</div>';

                $taggingTab .= '<hr>';

                //other sources metadata area
                $taggingTab .= '<div class="other-sources-metadata-area">';
                    /*$taggingTab .= '<p>';
                        $taggingTab .= '<span>';
                            $taggingTab .= 'Other sources';
                        $taggingTab .= '</span>';
                    $taggingTab .= '</p>';*/

                    $taggingTab .= '<div class="add-source-links">';
                        //add source link collapse heading
                        $taggingTab .= '<div class= "collapse-headline collapse-controller" data-toggle="collapse" href="#add-link-form"
                            onClick="">';
                            $taggingTab .= '<span id="adding-new-link"  title="Click to add a link" class="theme-color" href="#">';
                                $taggingTab .= 'Other Sources';
                                $taggingTab .= '<i class="fas fa-plus-circle" style="padding-left:6px;"></i>';
                            $taggingTab .= '</span>';
                        $taggingTab .= '</div>';
                                
                                // add source link form area
                                $taggingTab .= '<div id="add-link-form" class="collapse">';
                                    $taggingTab .= '<form id="add-linkinfo-form" action="" method="post">';
                                                    
                                        $taggingTab .= '<div>';
                                            $taggingTab .= "<span>Link:</span><br/>";
                                        $taggingTab .= '</div>';
                                        
                                        $taggingTab .= '<div id="add-link-container">';
                                            $taggingTab .= '<input class="saving-link-test" type="text" name="" placeholder="Enter URL here">';
                                        $taggingTab .= '</div>';

                                        $taggingTab .= '<div id="link-description-inputs" class="link-description-container">';
                                            $taggingTab .= '<label>Additional description:</label><br/>';
                                            $taggingTab .= '<textarea rows= "3" class="saving-link-test gsearch-form" type="text" id="ldsc" placeholder="" name=""></textarea>';
                                        $taggingTab .= '</div>';

                                        $taggingTab .= "<button type='submit' class='save-link-button theme-color-background' id='link-update-save-button' style='' onClick='updateItemTranscription(".$itemData['ItemId'].", ".get_current_user_id().")'>";
                                            $taggingTab .= "SAVE LINK";

                                            $taggingTab .= '<script>
                                                            function onButtonClick(){
                                                                document.getElementById("textInput").className="show";
                                                            }
                                                        </script>';

                                        $taggingTab .= "</button>";

                                        $taggingTab .= '<div style="clear:both;"></div>';
   
                                    $taggingTab .= '</form>';

                                $taggingTab .=    "</div>";

                                $taggingTab .= '<p id="saved-linkdata-demo"></p>';

                                    $taggingTab .= '<script>
                                                        jQuery(document).ready(function(){
                                                            var c=" ";
                                                            jQuery("#add-linkinfo-form").submit(function(){
                                                                jQuery(".saving-link-test").each(function(){
                                                                c += jQuery(this).val()+ "<br/>";
                                                                    
                                                                });
                                                                jQuery("#saved-linkdata-demo").html(c);
                                                                return false;
                                                            });
                                                        });
                                                    </script>';
                    $taggingTab .= '</div>';

                    $taggingTab .= '<hr>';


                    /*        $taggingTab .= '<div class="modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                <p>Modal body text goes here.</p>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                            </div>
                        </div>';
                        $taggingTab .= "<script>
                                            jQuery('#adding-new-link').on('shown.bs.modal', function () {
                                                jQuery('#myInput').trigger('focus')
                                            })
                                            </script>";
                                // The Modal 
                                $taggingTab .= '<div id="myothersources" class="modalsources">';
                                    //Modal content
                                    
                                    $taggingTab .= '<div class="modalsource-content">';
                                        $taggingTab .= '<form action="/action_page.php">';
                                                            
                                            $taggingTab .= '<div>';
                                                $taggingTab .= "<p style='float:left;'>Additional information on</p><br/><span style='float:left;'>Title</span>";
                                                $taggingTab .= '<span class="close">&times;</span>';
                                            $taggingTab .= '</div>';
                                            
                                            $taggingTab .= '<div>';
                                                $taggingTab .= '<p>In order to add additional information provided on other websites, 
                                                please enter a link, add a few words, what the user expects to find there and click 
                                                "Save link" below</p>';
                                            $taggingTab .= '</div>';

                                            $taggingTab .= '<div>';
                                                $taggingTab .= '<input type="text" id="person-entry" name="" placeholder="" style="outline:none;">';
                                                $taggingTab .= '<input type="text" id="person-descript" name="" placeholder="" style="outline:none; margin-left: 4px;">';
                                            $taggingTab .= '</div>';
                                                
                                            $taggingTab .= '<div>';
                                            $taggingTab .= '</div>';
            
            
                                            $taggingTab .= "<button class='save-transcription theme-color-background' id='link-update-button' style='float: center;' onClick='updateItemTranscription(".$itemData['ItemId'].", ".get_current_user_id().")'>";
                                                $taggingTab .= "SAVE LINK";
                                                $taggingTab .= '<script>
                                                                function onButtonClick(){
                                                                    document.getElementById("textInput").className="show";
                                                                }
                                                            </script>';
                                            $taggingTab .= "</button>";
                                                                
                                        $taggingTab .= '</form>';
                                    $taggingTab .=    "</div>";
                                $taggingTab .= "</div>";
                                    */
                $taggingTab .= '</div>';

            $taggingTab .= '</div>';

        // Help tab
        //$helpTab = "";
            //$helpTab .= "<p>test... help tab</p>";

        // Automatic enrichment tab
        /*$autoEnrichmentTab = "";
            $autoEnrichmentTab .= '<div id="automaticEnrichment-section" class="item-page-section">';
                $autoEnrichmentTab .= '<div id="automaticEnrichment-collapse-headline" class="item-page-section-headline-container collapse-headline item-page-section-collapse-headline collapse-controller" data-toggle="collapse" href="#enrichment-collapsable"
                onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')
                jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')">';
                    $autoEnrichmentTab .= '<h4 id="automaticEnrichment-collapse-heading" class="theme-color item-page-section-headline">';
                            $autoEnrichmentTab .= 'AUTOMATIC ENRICHMENTS';
                        $autoEnrichmentTab .= '</h4>';
                    $autoEnrichmentTab .= '<i id="automatic-enrichment-collapse-icon" class="far fa-caret-circle-down collapse-icon theme-color" style="font-size: 17px; float:left;  margin-right: 8px; margin-top: 9px;"></i>';
                $autoEnrichmentTab .= '</div>';
                $autoEnrichmentTab .= '<div id="description-status-changer" class="status-changer section-status-changer">';
                    $autoEnrichmentTab .= '<i id="automaticEnrichment-status-indicator" class="fal fa-circle status-indicator"
                                        style="color: '.$itemData['AutomaticEnrichmentStatusColorCode'].'; background-color:'.$itemData['AutomaticEnrichmentStatusColorCode'].';"
                                        onclick="document.getElementById(\'automaticEnrichment-status-dropdown\').classList.toggle(\'show\')"></i>';
                    $autoEnrichmentTab .= '<div id="automaticEnrichment-status-dropdown" class="sub-status status-dropdown-content">';
                        foreach ($statusTypes as $statusType) {
                            if ($itemData['AutomaticEnrichmentStatusId'] == $statusType['CompletionStatusId']) {
                                $autoEnrichmentTab .= "<div class='status-dropdown-option status-dropdown-option-current'
                                                    onclick=\"changeStatus(".$_GET['item'].",  '".$statusType['Name']."', 'AutomaticEnrichmentStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                $autoEnrichmentTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                            } else {
                                $autoEnrichmentTab .= "<div class='status-dropdown-option'
                                                    onclick=\"changeStatus(".$_GET['item'].",  '".$statusType['Name']."', 'AutomaticEnrichmentStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                $autoEnrichmentTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                            }
                        }
                    $autoEnrichmentTab .= '</div>';
                $autoEnrichmentTab .= '</div>';
                $autoEnrichmentTab .= '<div style="clear: both;"></div>';
                $autoEnrichmentTab .= "<div id='enrichment-collapsable' class='collapse'>";
                    $autoEnrichmentTab .= "<p>test... automatic enrichment tab</p>";
                $autoEnrichmentTab .= "</div>";
            $autoEnrichmentTab .= "</div>";*/

        // Comment section
        $commentSection = "";
        $commentSection .= '<div class="item-page-section">';
            $commentSection .= '<div class="item-page-section-headline-container collapse-headline item-page-section-collapse-headline collapse-controller" data-toggle="collapse" href="#comments"
                    onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')
                        jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')"
                        >';
                $commentSection .= '<h4 id="comments-collapse-heading" class="theme-color item-page-section-headline">';
                    $commentSection .= "NOTES AND QUESTIONS";
                $commentSection .= '</h4>';
                $commentSection .= '<i class="far fa-caret-circle-down collapse-icon theme-color" style="font-size: 17px; float:left; margin-right: 8px; margin-top: 9px;"></i>';
            $commentSection .= '</div>';
            $commentSection .= '<div style="clear: both;"></div>';
            $commentSection .= "<div id=\"comments\" class=\"comments-area collapse\">";
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
                        $commentSection .= "<textarea id=\"comment\" class=\"notes-questions\" rows=\"3\" name=\"comment\" aria-required=\"true\">";
                        $commentSection .= "</textarea>";
                        $commentSection .= "<input name=\"wpml_language_code\" type=\"hidden\" value=\"en\" />";
                        $commentSection .= "<p class=\"form-submit\">";
                            $commentSection .= "<input name=\"submit\" type=\"submit\" id=\"submit\" class=\"submit notes-questions-submit theme-color-background\" value=\"SAVE\" />";
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
                        $commentSection .= "<script>
                                            jQuery(document).ready(function(){                                        
                                            jQuery('.notes-questions').keyup(function() {
                                            var block_data = jQuery(this).val();
                                                    if(block_data.length==0){
                                                    jQuery('.notes-questions-submit').css('display','none');
                                                    }else{
                                                jQuery('.notes-questions-submit').css('display','block');
                                                }
                                            });
                                            });
                                        </script>";
                    $commentSection .= "</form>";
                $commentSection .= "</div><!-- #respond -->";
            $commentSection .= "</div><!-- #comments .comments-area -->";
        $commentSection .= '</div>';

        // View switcher button
        //$content .= "<button id='item-page-switcher' onclick='switchItemPageView()'>switch</button>";

        // <<< FULL VIEW >>> //

        $content .= "<div id='full-view-container'>";
          // Top image slider
        $content .= "<div class='item-page-slider  top-slider-full-view full-width-header test-width'>";
        $i = 1;
            foreach ($storyData['Items'] as $item) {
                $image = json_decode($item['ImageLink'], true);
                $imageLink = $image['service']['@id'];
                if ($image["width"] <= $image["height"]) {
                    $imageLink .= "/0,0,".$image["width"].",".$image["width"];
                }
                else {
                    $imageLink .= "/0,0,".$image["height"].",".$image["height"];
                }
                $imageLink .= "/150,150/0/default.jpg";
                if ($initialSlide == null && $item['ItemId'] == $_GET['item']){
                    $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."' class='slider-current-item'>";
                        $content .= "<div class='slider-current-item-pointer'></div>";

                        $content .= "<img data-lazy='".$imageLink."'>";
                    $content .= "</a>";
                    $initialSlide = $i;
                }
                else {
                    $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."'>";
                    $content .= "<div class='label-img-status shadow-img-corner'></div>";
                    $content .= "<div class='label-img-status review-img'></div>";
                   

                        $content .= "<img data-lazy='".$imageLink."'>";
                    $content .= "</a>";
                    $i++;
                }
            }
                /* 
                $content .= "<div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258363.full-150x150.jpg'></div>
                <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258364.full-150x150.jpg'></div>
                <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258365.full-150x150.jpg'></div>
                <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258366.full-150x150.jpg'></div>
                <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258367.full-150x150.jpg'></div>
                <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258368.full-150x150.jpg'></div>
                <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258369.full-150x150.jpg'></div>
                <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258370.full-150x150.jpg'></div>
                <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258371.full-150x150.jpg'></div>

                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>
                <div><img data-lazy='https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000162/square/150,150/0/default.jpg'></div>";*/

            $content .= "</div>";

                // Image slider JavaScript
                $infinite = "true";
                if (sizeof($storyData['Items']) > 100) {
                $infinite = "false";
                }
                $content .= "<script>
                        jQuery(document).ready(function(){
                            jQuery('.item-page-slider').slick({
                                dots: true,
                                infinite: ".$infinite.",
                                arrows: true,
                                speed: 300,
                                slidesToShow: 13,
                                slidesToScroll: 13,
                                lazyLoad: 'ondemand',
                                initialSlide: ".$initialSlide.",
                                responsive: [
                                    {
                                        breakpoint: 1720,
                                        settings: { slidesToShow: 11, slidesToScroll: 11, }
                                    },
                                    {
                                        breakpoint: 1570,
                                        settings: { slidesToShow: 10, slidesToScroll: 10, }
                                    },
                                    {
                                        breakpoint: 1420,
                                        settings: { slidesToShow: 9, slidesToScroll: 9, }
                                    },
                                    {
                                        breakpoint: 1270,
                                        settings: { slidesToShow: 8, slidesToScroll: 8, }
                                    },
                                    {
                                        breakpoint: 1120,
                                        settings: { slidesToShow: 7, slidesToScroll: 7, }
                                    },
                                    {
                                        breakpoint: 970,
                                        settings: { slidesToShow: 6, slidesToScroll: 6, }
                                    },
                                    {
                                        breakpoint: 820,
                                        settings: { slidesToShow: 5, slidesToScroll: 5, }
                                    },
                                    {
                                        breakpoint: 670,
                                        settings: { slidesToShow: 4, slidesToScroll: 4, }
                                    },
                                    {
                                        breakpoint: 520,
                                        settings: { slidesToShow: 3, slidesToScroll: 3, }
                                    },
                                ]
                            });
                        });
                    </script>";
            $content .= '<div class="item-navigation-area">';
                $content .= '<ul class="item-navigation-content-container left" style="">
                                <li><a href="">Stories</a></li>
                                <li><i class="fal fa-angle-right"></i></li>
                                <li><a href="">Title</a></li>
                                <li><i class="fal fa-angle-right"></i></li>
                                <li><span>item number</span></li>
                            </ul>';
                    $content .= '<ul class="item-navigation-content-container right" style="">
                                <li><a title="first" href=""><i class="fal fa-angle-double-left"></i></a></li>
                                <li class="rgt"><a title="previous" href=""><i class="fal fa-angle-left"></i></a></li>
                                <li class="rgt"><a title="Story:" href=""><i class="fal fa-book"></i></a></li>
                                <li class="rgt"><a title="next" href=""><i class="fal fa-angle-right"></i></a></li>
                                <li class="rgt"><a title="last"  href=""><i class="fal fa-angle-double-right"></i></a></li>
                            </ul>';
            $content .= '</div>';
            $content .= "<div id='primary-full-width'>";
                $content .= "<div id='full-view-left'>";
                    $content .= $imageViewer;

                    $content .= "<div id='full-view-editor'>";
                        $content .= $editorTab;
                    $content .= "</div>";

                    //$content .= "<hr>";

                    $content .= "<div id='full-view-comment'>";
                        $content .= $commentSection;
                    $content .= "</div>";
                $content .= "</div>";
                
                $content .= "<div id='full-view-right'>";

                    $content .= "<div id='full-view-tagging'>";
                        $content .= $taggingTab;
                    $content .= "</div>";

                    //$content .= "<hr>";

                    $content .= '<div class="item-page-section">';
                        $content .= "<div id='full-view-info'>";
                            $content .= $infoTab;
                        $content .= "</div>";
                    $content .= "</div>";

                    //$content .= "<hr>";

                    $content .= '<div id="full-view-autoEnrichment" >';
                        $content .= $autoEnrichmentTab;
                    $content .= '</div>';
                $content .= "</div>";
            $content .= "</div>";
        $content .= "</div>";


        // Splitscreen container
        $content .= "<div id='image-view-container' class='panel-container-horizontal' style='display:none'>";

            // Image section
            $content .= "<div id='item-image-section' class='panel-left'>";
                $content .= '<div id="openseadragonFS">';
                    $content .= '<div class="buttons" id="buttonsFS">';
                        $content .= '<div id="zoom-inFS"><i class="far fa-plus"></i>';
                        $content .= '</div>';
                        $content .= '<div id="zoom-outFS"><i class="far fa-minus"></i>';
                        $content .= '</div>';
                        $content .= '<div id="homeFS"><i class="far fa-home"></i>';
                        $content .= '</div>';
                        $content .= '<div id="full-widthFS"><i class="far fa-arrows-alt-h"></i>';
                        $content .= '</div>';
                        $content .= '<div id="rotate-rightFS"><i class="far fa-redo"></i>';
                        $content .= '</div>';
                        $content .= '<div id="rotate-leftFS"><i class="far fa-undo"></i>';
                        $content .= '</div>';
                        $content .= '<div id="filterButtonFS"><i class="far fa-sliders-h"></i>';
                        $content .= '</div>';
                        $content .= '<div id="full-pageFS"><i class="far fa-compress-arrows-alt"></i>';
                        $content .= '</div>';
                        if($isLoggedIn) {
                            $content .= '<div id="transcribe"><i class="far fa-pen"></i></div>';
                        } else {
                            $content .= '<div id="transcribe locked"><i class="far fa-lock" id="lock-loginFS"></i></div>';
                        }
                    $content .= '</div>';
                $content .= '</div>';
            $content .= "</div>";
            //test temporary
                
            // Resize slider
            $content .= '<div id="item-splitter" class="splitter-vertical">
                        </div>';

            // Info/Transcription section
            $content .= "<div id='item-data-section' class='panel-right'>";
                $content .= "<div id='item-data-header'>";
                    // Tab menu
                    $content .= '<ul id="item-tab-list" class="tab-list">';
                        $content .= "<li>";
                            $content .= "<div class='theme-color theme-color-hover tablinks active'
                                            onclick='switchItemTab(event, \"editor-tab\")'>";
                                $content .= '<i class="fal fa-pencil"></i>';
                            $content .= "</div>";
                        $content .= "</li>";

                        $content .= "<li>";
                            $content .= "<div class='theme-color theme-color-hover tablinks'
                                            onclick='switchItemTab(event, \"tagging-tab\")'>";
                                $content .= '<i class="fal fa-map-marker-alt" style="margin-left: -10.5px;"></i>';
                                $content .= '<i class="fal fa-tag" style="position: absolute; left: 9.5px; top: 1px;"></i>';
                            $content .= "</div>";
                        $content .= "</li>";

                        /*$content .= "<li>";
                            $content .= "<div class='theme-color theme-color-hover tablinks'
                                            onclick='switchItemTab(event, \"autoEnrichment-tab\")'>";
                                $content .= '<i class="fal fa-laptop" style="margin-left: -3px;"></i>';
                            $content .= "</div>";
                        $content .= "</li>";*/

                        $content .= "<li>";
                            $content .= "<div class='theme-color theme-color-hover tablinks'
                                            onclick='switchItemTab(event, \"info-tab\")'>";
                                $content .= '<i class="fal fa-info-square"></i>';
                            $content .= "</div>";
                        $content .= "</li>";

                        /*$content .= "<li>";
                            $content .= "<div class='theme-color theme-color-hover tablinks'
                                            onclick='switchItemTab(event, \"settings-tab\")'>";
                                $content .= '<i class="fal fa-sliders-h"></i>';
                            $content .= "</div>";
                        $content .= "</li>";*/

                        $content .= "<li>";
                            $content .= "<div class='theme-color theme-color-hover tablinks'
                                            onclick='switchItemTab(event, \"help-tab\")'>";
                                $content .= '<i class="fal fa-question-circle"></i>';
                            $content .= "</div>";
                        $content .= "</li>";
                    $content .= '</ul>';
                    //////////////////////////////////////////////////////////////////////////////////////
                     //Status Chart

                    // Set request parameters for status data
                    $url = network_home_url()."/tp-api/completionStatus";
                    $requestType = "GET";

                    // Execude http request
                    include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                    // Save status data
                    $statusTypes = json_decode($result, true);

                    // Save status data
                    $statusTypes = json_decode($result, true);


                                $content .= "<div id='item-status-doughnut-chart' class='item-status-chart'>";
                                    $content .= "<canvas id='item-status-doughnut-chart-canvas' style='display:inline;' width='38' height='38'>";
                                    $content .= "</canvas>";
                                    $content .= '<div id="item-status-doughnut-info-box" class="item-status-info-box">';
                                        $content .= '<ul class="item-status-info-box-list">';
                                            foreach ($statusTypes as $statusType) {
                                                $percentage = ($progressCount[$statusType['Name']] / sizeof($progressData)) * 100;
                                                $content .= '<li>';
                                                    $content .= '<span class="status-info-box-color-indicator" style="background-color:'.$statusType['ColorCode'].'; color:'.$statusType['ColorCode'].'; 
                                                                        background-image: -webkit-gradient(linear, left top, left bottom, 
                                                                        color-stop(0, '.$statusType['ColorCode'].'), color-stop(1, '.$statusType['ColorCodeGradient'].'));">';
                                                    $content .= '</span>';
                                                    $content .= '<span id="progress-doughnut-overlay-'.str_replace(' ', '-', $statusType['Name']).'-section" class="status-info-box-percentage" style="width: 20%;">';
                                                        $content .= $percentage.'%';
                                                    $content .= '</span>';
                                                    $content .= '<span class="status-info-box-text">';
                                                        $content .= $statusType['Name'];
                                                    $content .= '</span>';
                                                $content .= '</li>';
                                            }
                                        $content .= '</ul>';
                                    $content .= '</div>';
                                $content .= "</div>";

                                $content .= "<script>
                                                var ctx = document.getElementById('item-status-doughnut-chart-canvas');
                                                var statusDoughnutChart = new Chart(ctx, {
                                                    type: 'doughnut',
                                                    data: {
                                                        labels :['Not Started','Edit','Review','Completed'],
                                                        datasets: [{
                                                            data: [".$progressCount['Not Started'].", ".$progressCount['Edit'].", ".$progressCount['Review'].", ".$progressCount['Completed']."],
                                                            backgroundColor: [";
                                                                foreach ($statusTypes as $statusType) {
                                                                    $content .= '"'.$statusType['ColorCode'].'", ';
                                                                }
                                $content .=                 "],
                                                            borderWidth: 0
                                                        }]
                                                    },
                                                    options: {
                                                        cutoutPercentage: 26,
                                                        borderWidth: 4,
                                                        borderColor: '#000',
                                                        tooltips: {enabled: false},
                                                        hover: {mode: null},
                                                        legend : {
                                                                    display: false
                                                                },
                                                        responsive: false,
                                                        segmentShowStroke: false
                                                    },
                                                });
                                                function updateDoughnutStatus(oldStatusName, newStatusName) {
                                                    var labels = statusDoughnutChart.data.labels;
                                                    for (var i = 0; i < labels.length; i++){
                                                        if (labels[i] == oldStatusName) {
                                                            var oldStatusIndex = i;
                                                        }
                                                        if (labels[i] == newStatusName) {
                                                            var newStatusIndex = i;
                                                        }
                                                    }

                                                    statusDoughnutChart.data.datasets[0].data[oldStatusIndex] -= 1;
                                                    statusDoughnutChart.update();
                                                    statusDoughnutChart.data.datasets[0].data[newStatusIndex] += 1;
                                                    statusDoughnutChart.update();
                                                }
                                            </script>";



                    //////////////////////////////////////////////////////////////////////////////////////
                    // View switcher
                    $content .= '<div class="view-switcher">';
                        $content .= '<ul id="item-switch-list" class="switch-list">';

                            $content .= "<li>";
                                $content .= '<i id="popout" class="far fa-window-restore fa-rotate-180 view-switcher-icons theme-color"
                            onclick="switchItemView(event, \'popout\')"></i>';
                            $content .= "</li>";
                          
                            $content .= "<li>";
                                $content .= '<i id="vertical-split" class="far fa-window-maximize fa-rotate-180 view-switcher-icons theme-color"
                            onclick="switchItemView(event, \'vertical\')"></i>';
                            $content .= "</li>";

                            $content .= "<li>";
                                $content .= '<i id="horizontal-split" class="far fa-window-maximize fa-rotate-90 view-switcher-icons active theme-color" style="font-size:12px;"
                            onclick="switchItemView(event, \'horizontal\')"></i>';
                            $content .= "</li>";

                            $content .= "<li>";
                                $content .= '<i id="horizontal-split" class="fas fa-times view-switcher-icons theme-color"
                            onclick="switchItemView(event, \'horizontal\')"></i>';
                            $content .= "</li>";

                        $content .= '</ul>';
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
                        $content .= "<p class='theme-color item-page-section-headline'>Additional Information</p>";
                        // Content will be added here in switchItemPageView function
                    $content .= "</div>";

                    // Tagging tab
                    $content .= "<div id='tagging-tab' class='tabcontent' style='display:none;'>";
                        // Content will be added here in switchItemPageView function
                        
                    $content .= "</div>";

                    // Help tab
                    $content .= "<div id='help-tab' class='tabcontent' style='display:none;'>";
                        $content .= $helpTab;
                    $content .= "</div>";

                    // Automatic enrichment tab
                    $content .= "<div id='autoEnrichment-tab' class='tabcontent' style='display:none;'>";
                        // Content will be added here in switchItemPageView function
                    $content .= "</div>";

                $content .= "</div>";
            $content .= '</div>';

        // Split screen JavaScript
        $content .= '<script>
                        jQuery("#item-image-section").resizable_split({
                            handleSelector: "#item-splitter",
                            resizeHeight: false
                        });
                    </script>';

        $content .= "</div>
                </div>";
        echo $content;
    }
}
add_shortcode( 'item_page_test_ad', '_TCT_item_page_test_ad' );
?>