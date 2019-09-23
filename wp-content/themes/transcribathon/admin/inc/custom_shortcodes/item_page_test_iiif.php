<?php
/*
Shortcode: item_page
Description: Gets item data and builds the item page
*/

// include required files
include($_SERVER["DOCUMENT_ROOT"].'/wp-load.php');

function _TCT_item_page_test_iiif( $atts ) {
    global $ultimatemember;
    if (isset($_GET['item']) && $_GET['item'] != "") {
        // Set request parameters for image data
        $requestData = array(
            'key' => 'testKey'
        );
        $url = home_url()."/tp-api/items/".$_GET['item'];
        $requestType = "GET";
        $isLoggedIn = is_user_logged_in();

        // Execude http request
        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

        // Save image data
        $itemData = json_decode($result, true);
        $itemData = $itemData[0];

        // Set request parameters for story data
        $url = home_url()."/tp-api/stories/".$itemData['StoryId'];
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
                                color: ".$theme_sets['vantage_general_link_hover_color']." !important;
                            }

                            #transcription-selected-languages.language-selected ul li {
                                background: ".$theme_sets['vantage_general_link_hover_color']." ;
                                color: #ffffff;
                            }
                                                    
                            .item-page-slider button.slick-prev.slick-arrow:hover {
                                background: ".$theme_sets['vantage_general_link_hover_color']." ;
                                color: #ffffff;
                                opacity: 0.7;
                            }
                            
                            .item-page-slider button.slick-next.slick-arrow:hover {
                                background: ".$theme_sets['vantage_general_link_hover_color']." ;
                                color: #ffffff;
                                opacity: 0.7;
                            }              

                    </style>";

        $content .= '<script>
                        window.onclick = function(event) {
                            if (event.target.id != "transcription-status-indicator") {
                                var dropdown = document.getElementById("transcription-status-dropdown");
                                if (dropdown.classList.contains("show")) {
                                    dropdown.classList.remove("show");
                                }
                            }
                            if (event.target.id != "description-status-indicator") {
                                var dropdown = document.getElementById("description-status-dropdown");
                                if (dropdown.classList.contains("show")) {
                                    dropdown.classList.remove("show");
                                }
                            }
                            if (event.target.id != "location-status-indicator") {
                                var dropdown = document.getElementById("location-status-dropdown");
                                if (dropdown.classList.contains("show")) {
                                    dropdown.classList.remove("show");
                                }
                            }
                            if (event.target.id != "tagging-status-indicator") {
                                var dropdown = document.getElementById("tagging-status-dropdown");
                                if (dropdown.classList.contains("show")) {
                                    dropdown.classList.remove("show");
                                }
                            }
                        }
                    </script>';


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
            $url = home_url()."/tp-api/completionStatus";
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
                                                            onclick=\"changeStatus(".$_GET['item'].", null, '".$statusType['Name']."', 'TranscriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                        $editorTab .= "<i class='fal fa-circle' style='color: transparent;
                                                            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'>
                                                        </i>".$statusType['Name']."</div>";
                                    } else {
                                        $editorTab .= "<div class='status-dropdown-option'
                                                            onclick=\"changeStatus(".$_GET['item'].", null, '".$statusType['Name']."', 'TranscriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                        $editorTab .= "<i class='fal fa-circle' style='color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                                    }
                                }
                            $editorTab .= '</div>';
                        $editorTab .= '</div>';
                    $editorTab .= '</div>';
                $editorTab .= '</div>';
                $editorTab .= '<div style="clear: both;"></div>';

                $currentTranscription = null;
                $transcriptionList = [];
                if ($itemData["Transcriptions"] != null) {
                    $transcriptionData = $itemData["Transcriptions"];
                    foreach ($transcriptionData as $transcription) {
                        if ($transcription['CurrentVersion'] == "1") {
                            $currentTranscription = $transcription;
                        }
                        else {
                            array_push($transcriptionList, $transcription);
                        }
                    }
                }
                $editorTab .= '<div id="mce-wrapper-transcription">';
                    $editorTab .= '<div id="mytoolbar-transcription"></div>';
                    $editorTab .= '<div id="item-page-transcription-text" rows="4">';
                    if ($currentTranscription != null) {
                        $editorTab .= $currentTranscription['Text'];
                    }
                    $editorTab .= '</div>';
                $editorTab .= '</div>';

                   
              

                    $editorTab .= '<div id="transcription-language-selector" class="language-selector">';
                            // Set request parameters for language data
                        $url = home_url()."/tp-api/languages";
                        $requestType = "GET";

                            // Execude http request
                        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                            // Save language data
                        $languages = json_decode($result, true);

                        $editorTab .= '<select>';
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
                                            $editorTab .= "<li class='theme-colored-data-box'>";
                                                $editorTab .= $transcriptionLanguage['Name'];
                                                $editorTab .= '<script>
                                                            jQuery("#transcription-language-selector option[value=\''.$transcriptionLanguage['LanguageId'].'\'").prop("disabled", true)
                                                        </script>';
                                        $editorTab .= '<i class="far fa-times-circle" onClick="removeTranscriptionLanguage('.$transcriptionLanguage['LanguageId'].', this)"></i>';
                                        $editorTab .= '</li>';
                                        }
                            }
                        $editorTab .= '</ul>';
                    $editorTab .= '</div>';

                    $editorTab .= '<div class="transcription-metadata-container">';
                    $editorTab .= '<div id="no-text-selector">';
                        $editorTab .= '<label class="square-checkbox-container">';
                            $editorTab .= '<span>No Text</span>';
                            $noTextChecked = "";
                            if ($currentTranscription != null) {
                                if ($currentTranscription['NoText'] == "1") {
                                    $noTextChecked = "checked";
                                }
                            }
                            $editorTab .= '<input id="no-text-checkbox" type="checkbox" '.$noTextChecked.'>';
                            $editorTab .= '<span class="theme-color-background checkmark"></span>';
                        $editorTab .= '</label>';
                    $editorTab .= '</div>';


                    $editorTab .= "<button class='item-page-save-button theme-color-background' id='transcription-update-button' 
                                            onClick='updateItemTranscription(".$itemData['ItemId'].", ".get_current_user_id()."
                                                    , \"".$statusTypes[1]['ColorCode']."\", ".sizeof($progressData).")'>";
                        $editorTab .= "SAVE"; // save transcription
                    $editorTab .= "</button>";
                    $editorTab .= '<div id="item-transcription-spinner-container" class="spinner-container spinner-container-right">';
                        $editorTab .= '<div class="spinner"></div>';
                    $editorTab .= "</div>";
                    $editorTab .= "<div style='clear:both'></div>";
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
                                                    onclick=\"changeStatus(".$_GET['item'].", null, '".$statusType['Name']."', 'DescriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                $editorTab .= "<i class='fal fa-circle' style='color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                            } else {
                                $editorTab .= "<div class='status-dropdown-option'
                                                    onclick=\"changeStatus(".$_GET['item'].", null, '".$statusType['Name']."', 'DescriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                $editorTab .= "<i class='fal fa-circle' style='color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                            }
                        }
                    $editorTab .= '</div>';
                $editorTab .= '</div>';
                $editorTab .= '<div style="clear: both;"></div>';
                    $editorTab .= "<div id=\"description-area\" class=\"description-save collapse show\">";
                        $editorTab .= "<div id=\"category-checkboxes\">";
                            // Set request parameters for category data
                            $url = home_url()."/tp-api/properties?PropertyType=Category";
                            $requestType = "GET";

                            // Execude http request
                            include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                            // Save category data
                            $categories = json_decode($result, true);

                            foreach ($categories as $category) {
                                $checked = "";
                                if ($itemData['Properties'] != null) {
                                    foreach ($itemData['Properties'] as $itemProperty) {
                                        if ($itemProperty['PropertyId'] == $category['PropertyId']) {
                                            $checked = "checked";
                                            break;
                                        }
                                    }
                                }
                                $editorTab .= '<label class="square-checkbox-container">';
                                    $editorTab .= $category['PropertyValue'];
                                    $editorTab .= '<input class="category-checkbox" id="type-'.$category['PropertyValue'].'-checkbox" type="checkbox" '.$checked.'
                                                        name="'.$category['PropertyValue'].'"value="'.$category['PropertyId'].'"
                                                        onClick="addItemProperty('.$_GET['item'].', '.get_current_user_id().', this)">';
                                    $editorTab .= '<span  class="theme-color-background checkmark"></span>';
                                $editorTab .= '</label>';
                            }
                            $editorTab .= '<div style="clear: both;"></div>';
                        $editorTab .= '</div>';

                        $editorTab .= '<textarea id="item-page-description-text" name="description" rows="4">';
                            if ($itemData['Description'] != null) {
                                $editorTab .= $itemData['Description'];
                            }
                        $editorTab .= '</textarea>';


                        $editorTab .= '<div id= "description-language-selector" class="language-selector">';
                            $editorTab .= '<select>';
                                if ($itemData['DescriptionLanguage'] == null) {
                                    $editorTab .= '<option value="" disabled selected hidden>';
                                        $editorTab .= 'Please select a language...';
                                    $editorTab .= '</option>';
                                    foreach ($languages as $language) {
                                        $editorTab .= '<option value="'.$language['LanguageId'].'">';
                                            $editorTab .= $language['Name'];
                                        $editorTab .= '</option>';
                                    }
                                }
                                else {
                                    foreach ($languages as $language) {
                                        if ($itemData['DescriptionLanguage'] == $language['LanguageId']) {
                                            $editorTab .= '<option value="'.$language['LanguageId'].'" selected>';
                                                $editorTab .= $language['Name'];
                                            $editorTab .= '</option>';
                                        }
                                        else {
                                            $editorTab .= '<option value="'.$language['LanguageId'].'">';
                                                $editorTab .= $language['Name'];
                                            $editorTab .= '</option>';
                                        }
                                    }
                                }
                            $editorTab .= '</select>';
                        $editorTab .= '</div>';

                        $editorTab .= "<button class='theme-color-background' id='description-update-button' style='float: right;' 
                                            onClick='updateItemDescription(".$itemData['ItemId'].", ".get_current_user_id().", \"".$statusTypes[1]['ColorCode']."\", ".sizeof($progressData).")'>";
                            $editorTab .= "SAVE"; //save description
                        $editorTab .= "</button>";
                        $editorTab .= '<div id="item-description-spinner-container" class="spinner-container spinner-container-right">';
                            $editorTab .= '<div class="spinner"></div>';
                        $editorTab .= "</div>";
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
                    $editorTab .= '</div>';

                    $editorTab .= '<div id="transcription-'.$i.'" class="collapse transcription-history-collapse-content">';
                        $editorTab .= '<p id="item-page-current-transcription">';
                            $editorTab .= $transcription['Text'];
                        $editorTab .= '</p>';
                        $editorTab .= '<input class="transcription-comparison-button" type="button"
                                            onClick="compareTranscription(\''.$transcriptionList[$i]['Text'].'\', \''.$currentTranscription['Text'].'\','.$i.')"
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
            $infoTab .= '<div style="clear: both;"></div>';

            $infoTab .= '<div id="additional-information-area">';
                $infoTab .= "<h4 class='theme-color item-page-section-headline'>";
                    $infoTab .= "Title: ".$itemData['Title'];
                $infoTab .= "</h4>";
                $infoTab .= "<p class='item-page-property-value'>";
                    $infoTab .= $itemData['Description'];
                $infoTab .= "</p>";

                // Set request parameters
                $url = home_url()."/tp-api/fieldMappings";
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
                                    $infoTab .= "<a href=\"".$value."\" target=\"_blank\" rel=\"noopener noreferrer\">".$value."</a>";
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
            // Location section
	    $taggingTab .= "<div id='full-view-map'>";
	    $taggingTab .= "</div>";
	    $taggingTab .= "<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.4.1/mapbox-gl-geocoder.min.js'></script>
						<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.4.1/mapbox-gl-geocoder.css' type='text/css' />";
	    $taggingTab .= "    <script>
							jQuery(document).ready(function() {
						        var url_string = window.location.href;
							var url = new URL(url_string);
							var itemId = url.searchParams.get('item');
							var coordinates = jQuery('.location-input-coordinates-container.location-input-container > input ')[0];

							    mapboxgl.accessToken = 'pk.eyJ1IjoiZmFuZGYiLCJhIjoiY2pucHoybmF6MG5uMDN4cGY5dnk4aW80NSJ9.U8roKG6-JV49VZw5ji6YiQ';
							    var map = new mapboxgl.Map({
							      container: 'full-view-map',
							      style: 'mapbox://styles/fandf/cjnpzoia60m4y2rp5cvoq9t8z',
							      center: [62.8, -21],
							      zoom: 1
							    });
							map.addControl(new mapboxgl.NavigationControl());
							
								fetch('/dev/tp-api/items/' + itemId)
							                        .then(function(response) {
							                          return response.json();
							                        })
							                        .then(function(places) {
							                          console.log(places);
							                          places[0].Places.forEach(function(marker) {
							                            var el = document.createElement('div');
							                            el.className = 'marker savedMarker fas fa-map-marker-alt';
										      var popup = new mapboxgl.Popup({offset: 25})
        										.setHTML('<div class=\"popupWrapper\"><div class=\"name\">' + marker.Name + '</div><div class=\"comment\">' + marker.Comment + '</div></div>');

							                            new mapboxgl.Marker({element: el, anchor: 'bottom'})
							                              .setLngLat([marker.Longitude, marker.Latitude])
										      .setPopup(popup)
							                              .addTo(map);
							                          })
                      						  });

							    var geocoder = new MapboxGeocoder({
							      accessToken: mapboxgl.accessToken,
							      mapboxgl: mapboxgl,
      							      marker: false
							    });
							
							    geocoder.on('result', function(res) {
							      console.log(res);
							      jQuery('#location-input-section').addClass('show');
							      jQuery('.location-input-name-container.location-input-container > input').val(res.result.place_name);
							      jQuery('#location-input-geonames-search-container > input').val(res.result.properties.wikidata);
							      var el = document.createElement('div');
							      el.className = 'marker';
							
								var icon = document.createElement('i');
								icon .className = 'fas fa-map-marker-plus';
							      marker = new mapboxgl.Marker({element: el, draggable: true, element: icon})
							        .setLngLat(res.result.geometry.coordinates)
							        .addTo(map);
							        var lngLat = marker.getLngLat();
								coordinates.value = lngLat.lat + ', ' + lngLat.lng;
							marker.on('dragend', onDragEnd);
							    })
							
							    map.addControl(geocoder, 'bottom-left');
							    var marker;
							    jQuery('#addMarker').click(function() {
							      var el = document.createElement('div');
							      el.className = 'marker';
							
							      // make a marker for each feature and add to the map
							      marker = new mapboxgl.Marker({element: el, draggable: true})
							        .setLngLat(map.getCenter())
							        .addTo(map);
	
							marker.on('dragend', onDragEnd);
							    });

							

							    function onDragEnd() {
								var lngLat = marker.getLngLat();
								coordinates.value = lngLat.lng + ', ' + lngLat.lat;
							    }
								 
								
							
							    jQuery('#location-input-section > div:nth-child(4) > button:nth-child(1)').click(function() {
							      marker.setDraggable(false);
							      marker.getElement().classList.remove('fa-map-marker-plus');
							      marker.getElement().classList.add('fa-map-marker-alt');
							      marker.getElement().classList.add('savedMarker');
							      // set the popup
							      var name = jQuery('#location-input-section > div:nth-child(1) > div:nth-child(1) > input:nth-child(3)').val();
							     var desc = jQuery('#location-input-section > div:nth-child(2) > textarea:nth-child(3)').val();
							      var popup = new mapboxgl.Popup({offset: 25})
        										.setHTML('<div class=\"popupWrapper\"><div class=\"name\">' + name + '</div><div class=\"comment\">' + desc + '</div></div>');
							      marker.setPopup(popup);
							      console.log(marker._lngLat);
						    	    });
						});
    						</script>";
	           $taggingTab .= "<div id='location-section' class='item-page-section'>";
           
           $taggingTab .= "<div class='item-page-section-headline-container collapse-headline collapse-controller' data-toggle='collapse' href='#location-input-section' onClick=''>";
               $taggingTab .= "<i class='fal fa-map-marker-alt theme-color' style='padding-right: 3px; font-size: 17px; margin-right:8px;'></i>";
               $taggingTab .= "<h4 class='theme-color item-page-section-headline'>";
                   $taggingTab .= "Locations";
                   $taggingTab .= '<i class="fas fa-plus-circle" style="margin-left:5px; font-size:15px;"></i>';
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
                                                       onclick=\"changeStatus(".$_GET['item'].", null, '".$statusType['Name']."', 'LocationStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                   $taggingTab .= "<i class='fal fa-circle' style='color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                               } else {
                                   $taggingTab .= "<div class='status-dropdown-option'
                                                       onclick=\"changeStatus(".$_GET['item'].", null, '".$statusType['Name']."', 'LocationStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                   $taggingTab .= "<i class='fal fa-circle' style='color: transparent;background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                               }
                           }
                       $taggingTab .= '</div>';
                   $taggingTab .= '</div>';
               $taggingTab .= '</div>';
           $taggingTab .= "</div>";

           $taggingTab .= '<div id="location-input-section" class="collapse">';
               $taggingTab .= '<div class="location-input-section-top">';
                   $taggingTab .= '<div class="location-input-name-container location-input-container">';
                       $taggingTab .= '<label>Location name:</label><br/>';
                       $taggingTab .= '<input type="text" name="" placeholder="">';
                   $taggingTab .= '</div>';
                   $taggingTab .= '<div class="location-input-coordinates-container location-input-container">';
                       $taggingTab .=    '<label>Coordinates: </label>';
                       $taggingTab .=    '<span class="required-field">*</span>';
                       $taggingTab .=    '<br/>';
                       $taggingTab .=    '<input type="text" name="" placeholder="e.g.: 10.0123, 15.2345">';
                   $taggingTab .= '</div>';
                   $taggingTab .= "<div style='clear:both;'></div>";
               $taggingTab .= '</div>';

               $taggingTab .= '<div class="location-input-description-container location-input-container">';
                   $taggingTab .= '<label>Description:</label><br/>';
                   $taggingTab .= '<textarea rows= "2" style="resize:none;" class="gsearch-form" type="text" id="ldsc" placeholder="" name=""></textarea>';
               $taggingTab .= '</div>';

               $taggingTab .= '<div id="location-input-geonames-search-container" class="location-input-container location-search-container">';
                   $taggingTab .= '<label>WikiData reference:</label><br/>';
                   $taggingTab .= '<input type="text" id="lgns" placeholder="" name="">';
                   $taggingTab .= '<a id="geonames-search-button" href="">';
                       $taggingTab .= '<i class="far fa-search"></i>';
                   $taggingTab .= '</a>';
               $taggingTab .= '</div>';

               $taggingTab .= "<div class='form-buttons-right'>";
                   $taggingTab .= "<button class='item-page-save-button edit-data-save-right theme-color-background'
                                       onClick='saveItemLocation(".$itemData['ItemId'].", ".get_current_user_id()."
                                               , \"".$statusTypes[1]['ColorCode']."\", ".sizeof($progressData).")'>";
                       $taggingTab .= "SAVE";
                   $taggingTab .= "</button>";
                   $taggingTab .= '<div id="item-location-spinner-container" class="spinner-container spinner-container-right">';
                       $taggingTab .= '<div class="spinner"></div>';
                   $taggingTab .= "</div>";
                   $taggingTab .= "<div style='clear:both;'></div>";
               $taggingTab .= "</div>";
               $taggingTab .= "<div style='clear:both;'></div>";
           $taggingTab .=    "</div>";

           $taggingTab .= '<div id="item-location-list" class="item-data-output-list">';
           $taggingTab .= '<ul>';
               foreach ($itemData['Places'] as $place) {
                   if ($place['Comment'] != "NULL") {
                       $comment = $place['Comment'];
                   }
                   else {
                       $comment = "";
                   } 
                   $taggingTab .= '<li id="location-'.$place['PlaceId'].'">';
                       $taggingTab .= '<div class="item-data-output-element-header collapse-controller" data-toggle="collapse" href="#location-data-output-'.$place['PlaceId'].'">';
                           $taggingTab .= '<h6>';
                               $taggingTab .= $place['Name'];
                           $taggingTab .= '</h6>';
                           $taggingTab .= '<i class="fas fa-angle-down" style= "float:right;"></i>';
                            $taggingTab .= '<div style="clear:both;"></div>';
                       $taggingTab .= '</div>';

                     $taggingTab .= '<div id="location-data-output-'.$place['PlaceId'].'" class="collapse">';
                         $taggingTab .= '<div id="location-data-output-display-'.$place['PlaceId'].'" class="location-data-output-content">';
                             $taggingTab .= '<span>';
                                 $taggingTab .= 'Description: ';
                                 $taggingTab .= $comment;
                             $taggingTab .= '</span>';
                             $taggingTab .= '<i class="edit-item-data-icon fas fa-pencil theme-color-hover" 
                                                 onClick="openLocationEdit('.$place['PlaceId'].')"></i>';
                             $taggingTab .= '<i class="edit-item-data-icon fas fa-trash-alt theme-color-hover" 
                                                 onClick="deleteItemData(\'places\', '.$place['PlaceId'].', '.$_GET['item'].', \'place\', '.get_current_user_id().')"></i>';
                         $taggingTab .= '</div>';

                         $taggingTab .= '<div id="location-data-edit-'.$place['PlaceId'].'" class="location-data-edit-container">';
                            $taggingTab .= '<div class="location-input-section-top">';
                                $taggingTab .= '<div class="location-input-name-container location-input-container">';
                                    $taggingTab .= '<label>Location name:</label><br/>';
                                    $taggingTab .= '<input type="text" value="'.$place['Name'].'" name="" placeholder="">';
                                $taggingTab .= '</div>';
                                $taggingTab .= '<div class="location-input-coordinates-container location-input-container">';
                                    $taggingTab .=    '<label>Coordinates: </label>';
                                    $taggingTab .=    '<span class="required-field">*</span>';
                                    $taggingTab .=    '<br/>';
                                    $taggingTab .=    '<input type="text" value="'.$place['Latitude'].','.$place['Longitude'].'" name="" placeholder="">';
                                $taggingTab .= '</div>';
                                $taggingTab .= "<div style='clear:both;'></div>";
                            $taggingTab .= '</div>';

                            $taggingTab .= '<div class="location-input-description-container location-input-container">';
                                $taggingTab .= '<label>Description:</label><br/>';
                                $taggingTab .= '<textarea rows= "2" style="resize:none;" class="gsearch-form" type="text" id="ldsc">'.$comment.'</textarea>';
                            $taggingTab .= '</div>';

                            $taggingTab .= '<div id="location-input-geonames-search-container" class="location-input-container location-search-container">';
                                $taggingTab .= '<label>WikiData:</label><br/>';
                                $taggingTab .= '<input type="text" id="lgns" placeholder="" name="">';
                                $taggingTab .= '<a id="geonames-search-button" href="">';
                                    $taggingTab .= '<i class="far fa-search"></i>';
                                $taggingTab .= '</a>';
                            $taggingTab .= '</div>';
            
                            $taggingTab .= "<div class='form-buttons-right'>";
                                $taggingTab .= "<button class='item-page-save-button theme-color-background edit-data-save-right'
                                                    onClick='editItemLocation(".$place['PlaceId'].", ".$_GET['item'].", ".get_current_user_id().")'>";
                                    $taggingTab .= "SAVE";
                                $taggingTab .= "</button>";

                                $taggingTab .= "<button class='theme-color-background edit-data-cancel-right' onClick='openLocationEdit(".$place['PlaceId'].")'>";
                                    $taggingTab .= "CANCEL";
                                $taggingTab .= "</button>";
                                $taggingTab .= '<div id="item-location-'.$place['PlaceId'].'-spinner-container" class="spinner-container spinner-container-right">';
                                    $taggingTab .= '<div class="spinner"></div>';
                                $taggingTab .= "</div>";
                                $taggingTab .= "<div style='clear:both;'></div>";
                            $taggingTab .= "</div>";
                            $taggingTab .= "<div style='clear:both;'></div>";
                         $taggingTab .=    "</div>";
                     $taggingTab .=    "</div>";


                       $taggingTab .= '</li>';
                   }
               $taggingTab .= '</ul>';
           $taggingTab .= '</div>';
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
                                                            onclick=\"changeStatus(".$_GET['item'].", null, '".$statusType['Name']."', 'TaggingtatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                        $taggingTab .= "<i class='fal fa-circle' style='color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                                    } else {
                                        $taggingTab .= "<div class='status-dropdown-option'
                                                            onclick=\"changeStatus(".$_GET['item'].", null, '".$statusType['Name']."', 'TaggingStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                        $taggingTab .= "<i class='fal fa-circle' style='color: transparent;background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></i>".$statusType['Name']."</div>";
                                    }
                                }
                            $taggingTab .= '</div>';
                        $taggingTab .= '</div>';
                    $taggingTab .= '</div>';
                $taggingTab .= '</div>';

                $taggingTab .= '<div id="item-date-container">';
                    $taggingTab .= '<h6 class="theme-color item-data-input-headline">';
                        $taggingTab .= 'Document date';
                    $taggingTab .= '</h6>';
                        $taggingTab .= '<div class="item-date-inner-container">';
                        $taggingTab .= '<label>';
                            $taggingTab .= 'Start Date';
                        $taggingTab .= '</label>';
                        if ($itemData['DateStart'] != null) {
                                $startTimestamp = strtotime($itemData['DateStart']);
                                $dateStart = date("d/m/Y", $startTimestamp);
                                $taggingTab .= '<div class="item-date-display-container">';
                                    $taggingTab .= '<span type="text" id="startdateDisplay" class="item-date-display">';
                                        $taggingTab .= $dateStart;
                                    $taggingTab .= '</span>';
                                    $taggingTab .= '<i class="edit-item-date edit-item-data-icon fas fa-pencil theme-color-hover"></i>';
                                $taggingTab .= '</div>';
                                $taggingTab .= '<div class="item-date-input-container" style="display:none">';
                                    $taggingTab .= '<input type="text" id="startdateentry" value="'.$dateStart.'">';
                                $taggingTab .= '</div>';
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
                                $taggingTab .= '<div class="item-date-display-container">';
                                    $taggingTab .= '<span type="text" id="enddateDisplay" class="item-date-display">';
                                        $taggingTab .= $dateEnd;
                                    $taggingTab .= '</span>';
                                    $taggingTab .= '<i class="edit-item-date edit-item-data-icon fas fa-pencil theme-color-hover" 
                                                        onClick="editPerson('.$person['PersonId'].')"></i>';
                                $taggingTab .= '</div>';
                                $taggingTab .= '<div class="item-date-input-container" style="display:none">';
                                    $taggingTab .= '<input type="text" id="enddateentry" value="'.$dateEnd.'">';
                                $taggingTab .= '</div>';
                            }
                            else {
                                $taggingTab .= '<input type="text" id="enddateentry" placeholder="dd/mm/yyyy">';
                            }
                        $taggingTab .= "</div>";
                        $taggingTab .= "<button class='item-page-save-button theme-color-background' id='item-date-save-button' 
                                            onClick='saveItemDate(".$itemData['ItemId'].", ".get_current_user_id()."
                                            , \"".$statusTypes[1]['ColorCode']."\", ".sizeof($progressData).")'>";
                            $taggingTab .= "SAVE DATE";
                        $taggingTab .= "</button>";
                        $taggingTab .= '<div id="item-date-spinner-container" class="spinner-container spinner-container-right">';
                            $taggingTab .= '<div class="spinner"></div>';
                        $taggingTab .= "</div>";
                        $taggingTab .= '<div style="clear:both;"></div>';
                $taggingTab .= '</div>';

                $taggingTab .= '<hr>';

                //add person metadata area
                $taggingTab .= '<div class="item-page-person-container">'; 
                    //add person collapse heading 
                    $taggingTab .= '<div id="item-page-person-headline" class="collapse-headline collapse-controller theme-color" data-toggle="collapse" href="#person-input-container"
                                        onClick="
                                            jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')
                                            jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')">';                                          
                        $taggingTab .= '<h6 class="theme-color item-data-input-headline" title="Click to add Person data">';
                            $taggingTab .= 'Add Person data';
                            $taggingTab .= '<i class="fas fa-plus-circle"></i>';
                        $taggingTab .= '</h6>';
                    $taggingTab .= '</div>';

                    // add person form area
                    $taggingTab .= '<div class="collapse person-item-data-container" id="person-input-container">';
                        $taggingTab .= '<div id="person-input-names-container">';
                            $taggingTab .= '<input type="text" id="person-firstName-input" class="person-input-field" name="" placeholder="First Name" style="outline:none;">';
                            $taggingTab .= '<input type="text" id="person-lastName-input" class="person-input-field" name="" placeholder="Last Name">';
                        $taggingTab .= '</div>'; 

                        $taggingTab .= '<div id="person-location-birth-inputs">';
                            $taggingTab .= '<input type="text" id="person-birthPlace-input"   class="person-input-field" name="" placeholder="Birth Location">';
                            $taggingTab .= '<input type="text" id="person-birthDate-input" class="person-input-field" name="" placeholder="Birth: dd/mm/yyyy">';
                        $taggingTab .= '</div>'; 

                        $taggingTab .= '<div id="person-location-death-inputs">';
                            $taggingTab .= '<input type="text" id="person-deathPlace-input" class="person-input-field" name="" placeholder="Death Location">';
                            $taggingTab .= '<input type="text" id="person-deathDate-input" class="person-input-field" name="" placeholder="Death: dd/mm/yyyy">';
                        $taggingTab .= '</div>';    

                        $taggingTab .= '<div id="person-description-input">';
                            $taggingTab .= '<label>Additional description:</label><br/>';
                            $taggingTab .= '<input type="text" class="person-input-field">';
                        $taggingTab .= '</div>';

                        $taggingTab .= "<button id='save-personinfo-button' class='theme-color-background' id='person-save-button' 
                                            onClick='savePerson(".$itemData['ItemId'].", ".get_current_user_id()."
                                                    , \"".$statusTypes[1]['ColorCode']."\", ".sizeof($progressData).")'>";
                            $taggingTab .= "SAVE";
                        $taggingTab .= "</button>";
                        $taggingTab .= '<div id="item-person-spinner-container" class="spinner-container spinner-container-left">';
                            $taggingTab .= '<div class="spinner"></div>';
                        $taggingTab .= "</div>";

                        $taggingTab .= '<div style="clear:both;"></div>';           
                    $taggingTab .= '</div>';

                    $taggingTab .= '<div id="item-person-list" class="item-data-output-list">';
                        $taggingTab .= '<ul>';
                            foreach ($itemData['Persons'] as $person) {
                                if ($person['FirstName'] != "NULL") {
                                    $firstName = $person['FirstName'];
                                }
                                else {
                                    $firstName = "";
                                } 
                                if ($person['LastName'] != "NULL") {
                                    $lastName = $person['LastName'];
                                }
                                else {
                                    $lastName = "";
                                } 
                                if ($person['BirthPlace'] != "NULL") {
                                    $birthPlace = $person['BirthPlace'];
                                }
                                else {
                                    $birthPlace = "";
                                } 
                                if ($person['BirthDate'] != "NULL") {
                                    $birthTimestamp = strtotime($person['BirthDate']);
                                    $birthDate = date("d/m/Y", $birthTimestamp);
                                }
                                else {
                                    $birthDate = "";
                                } 
                                if ($person['DeathPlace'] != "NULL") {
                                    $deathPlace = $person['DeathPlace'];
                                }
                                else {
                                    $deathPlace = "";
                                } 
                                if ($person['DeathDate'] != "NULL") {
                                    $deathTimestamp = strtotime($person['DeathDate']);
                                    $deathDate = date("d/m/Y", $deathTimestamp);
                                }
                                else {
                                    $deathDate = "";
                                } 
                                if ($person['Description'] != "NULL") {
                                    $description = $person['Description'];
                                }
                                else {
                                    $description = "";
                                } 

                                $personHeadline = $firstName . ', ' . $lastName . ' ';
                                if ($birthDate != "") {
                                  if ($deathDate != "") {
                                    $personHeadline .= '(' . $birthDate . ' - ' . $deathDate . ')';
                                  }
                                  else {
                                    $personHeadline .= '(Birth: ' . $birthDate . ')';
                                  }
                                }
                                else {
                                  if ($deathDate != "") {
                                    $personHeadline .= '(Death: ' . $deathDate . ')';
                                  }
                                  else {
                                    if ($description != "") {
                                        $personHeadline .= '('.$description.')';
                                    }
                                  }
                                }
                                $taggingTab .= '<li id="person-'.$person['PersonId'].'">';
                                    $taggingTab .= '<div class="item-data-output-element-header collapse-controller" data-toggle="collapse" href="#person-data-output-'.$person['PersonId'].'">';
                                        $taggingTab .= '<h6 class="person-data-ouput-headline">';
                                            $taggingTab .= $personHeadline;
                                        $taggingTab .= '</h6>';
                                        $taggingTab .= '<span class="person-dots" style="display: none">. . .)</span>';
                                        $taggingTab .= '<i class="fas fa-angle-down" style= "float:right;"></i>';
                                        $taggingTab .= '<div style="clear:both;"></div>';
                                    $taggingTab .= '</div>';

                                    $taggingTab .= '<div id="person-data-output-'.$person['PersonId'].'" class="collapse">';
                                        $taggingTab .= '<div id="person-data-output-display-'.$person['PersonId'].'" class="person-data-output-content">';
                                            $taggingTab .= '<div class="person-data-output-birthDeath">';
                                                $taggingTab .= '<span>';
                                                    $taggingTab .= 'Birth Location: ';
                                                    $taggingTab .= $birthPlace;
                                                $taggingTab .= '</span>';
                                                $taggingTab .= '</br>';
                                                $taggingTab .= '<span>';
                                                    $taggingTab .= 'Death Location: ';
                                                    $taggingTab .= $deathPlace;
                                                $taggingTab .= '</span>';
                                            $taggingTab .= '</div>';
                                            $taggingTab .= '<div class="person-data-output-birthDeath">';
                                                $taggingTab .= '<span>';
                                                    $taggingTab .= 'Birth Date: ';
                                                    $taggingTab .= $birthDate;
                                                $taggingTab .= '</span>';
                                                $taggingTab .= '</br>';
                                                $taggingTab .= '<span>';
                                                    $taggingTab .= 'Death Date: ';
                                                    $taggingTab .= $deathDate;
                                                $taggingTab .= '</span>';

                                                $taggingTab .= '</br>';
                                            $taggingTab .= '</div>';
                                            $taggingTab .= '<div class="person-data-output-button">';
                                                    $taggingTab .= '<span>';
                                                        $taggingTab .= 'Description: ';
                                                        $taggingTab .= $description;
                                                    $taggingTab .= '</span>';
                                                    $taggingTab .= '<i class="edit-item-data-icon fas fa-pencil theme-color-hover" 
                                                                        onClick="editPerson('.$person['PersonId'].')"></i>';
                                                    $taggingTab .= '<i class="edit-item-data-icon fas fa-trash-alt theme-color-hover" 
                                                                        onClick="deleteItemData(\'persons\', '.$person['PersonId'].', '.$_GET['item'].', \'person\')"></i>';
                                                $taggingTab .= '</div>';
                                            $taggingTab .= '<div style="clear:both;"></div>';  
                                        $taggingTab .= '</div>';

                                        $taggingTab .= '<div class="person-data-edit-container person-item-data-container" id="person-data-edit-'.$person['PersonId'].'">';
                                            $taggingTab .= '<div id="person-edit-names-container">';
                                                $taggingTab .= '<input type="text" id="person-'.$person['PersonId'].'-firstName-edit" class="person-input-field" value="'.$firstName.'" style="outline:none;">';
                                                $taggingTab .= '<input type="text" id="person-'.$person['PersonId'].'-lastName-edit" class="person-input-field" value="'.$lastName.'">';
                                            $taggingTab .= '</div>'; 
    
                                            $taggingTab .= '<div id="person-location-birth-inputs">';
                                                $taggingTab .= '<input type="text" id="person-'.$person['PersonId'].'-birthPlace-edit"   class="person-input-field" value="'.$birthPlace.'">';
                                                $taggingTab .= '<input type="text" id="person-'.$person['PersonId'].'-birthDate-edit" class="person-input-field" value="'.$birthDate.'">';
                                            $taggingTab .= '</div>'; 
    
                                            $taggingTab .= '<div id="person-location-death-inputs">';
                                                $taggingTab .= '<input type="text" id="person-'.$person['PersonId'].'-deathPlace-edit" class="person-input-field" value="'.$deathPlace.'">';
                                                $taggingTab .= '<input type="text" id="person-'.$person['PersonId'].'-deathDate-edit" class="person-input-field" value="'.$deathDate.'">';
                                            $taggingTab .= '</div>';    
    
                                            $taggingTab .= '<div id="person-description-input">';
                                                $taggingTab .= '<label>Additional description:</label><br/>';
                                                $taggingTab .= '<input type="text" id="person-'.$person['PersonId'].'-description-edit" class="person-edit-field" value="'.$description.'">';
                                            $taggingTab .= '</div>';

                                            $taggingTab .= "<button id='save-personinfo-button' class='theme-color-background' id='person-save-button'>";
                                                $taggingTab .= "SAVE";
                                            $taggingTab .= "</button>";

                                            $taggingTab .= "<button id='save-personinfo-button' class='theme-color-background person-edit-data-cancel' onClick='editPerson(".$person['PersonId'].")'>";
                                                $taggingTab .= "CANCEL";
                                            $taggingTab .= "</button>";

                                            $taggingTab .= '<div id="item-person-spinner-container" class="spinner-container spinner-container-left">';
                                                $taggingTab .= '<div class="spinner"></div>';
                                            $taggingTab .= "</div>";
                                            $taggingTab .= '<div style="clear:both;"></div>';           
                                        $taggingTab .= '</div>';
                                    $taggingTab .= '</div>';

                                $taggingTab .= '</li>';
                            }
                        $taggingTab .= '</ul>';
                    $taggingTab .= '</div>';
                                        
                $taggingTab .= '</div>';

                $taggingTab .= '<hr>';

                //key word metadata area
                $taggingTab .= '<div id="item-page-keyword-container">';
                $taggingTab .= '<div id="item-page-person-headline" class="collapse-headline collapse-controller" data-toggle="collapse" href="#keyword-input-container">';                                   
                $taggingTab .= '<h6 class="theme-color item-data-input-headline" title="Click to add keywords">';
                        $taggingTab .= 'Keywords';
                        $taggingTab .= '<i class="fas fa-plus-circle"></i>';
                    $taggingTab .= '</h6>';
                $taggingTab .= '</div>';
                $taggingTab .= '<div id="keyword-input-container" class="collapse">';
                    $taggingTab .= '<input type="text" id="keyword-input" name="" placeholder="">';
                    $taggingTab .= "<button id='keyword-save-button' type='submit' class='theme-color-background'
                                        onClick='saveKeyword(".$itemData['ItemId'].", ".get_current_user_id()."
                                        , \"".$statusTypes[1]['ColorCode']."\", ".sizeof($progressData).")'>";
                        $taggingTab .= 'SAVE';
                    $taggingTab .= '</button>';
                    $taggingTab .= '<div id="item-keyword-spinner-container" class="spinner-container spinner-container-left">';
                        $taggingTab .= '<div class="spinner"></div>';
                    $taggingTab .= "</div>";
                    $taggingTab .= '<div style="clear: both;"></div>';
                $taggingTab .= '</div>';

                $taggingTab .= '<div id="item-keyword-list" class="item-data-output-listt">';
                $taggingTab .= '<ul>';
                    foreach ($itemData['Properties'] as $property) {
                        if ($property['PropertyType'] == "Keyword") {
                            $taggingTab .= '<li id="add-item-keyword" class="theme-color-background">';
                                        $taggingTab .= $property['PropertyValue'];
                                    $taggingTab .= '<i class="delete-item-datas far fa-times-circle"
                                                        onClick="deleteItemData(\'properties\', '.$property['PropertyId'].', '.$_GET['item'].', \'keyword\')"></i>';
                            
                                  $taggingTab .= '</li>';
                              }
                          }
                      $taggingTab .= '</ul>';
                  $taggingTab .= '</div>';
              $taggingTab .= '</div>';
        

              $taggingTab .= '<hr>';
                //other sources metadata area
                $taggingTab .= '<div id="item-page-link-container">';
                    //add source link collapse heading
                    $taggingTab .= '<div class= "collapse-headline collapse-controller" data-toggle="collapse" href="#link-input-container">';
                    $taggingTab .= '<h6 class="theme-color item-data-input-headline" title="Click to add a link">';
                            $taggingTab .= 'Other Sources';
                            $taggingTab .= '<i class="fas fa-plus-circle"></i>';
                        $taggingTab .= '</h6>';
                    $taggingTab .= '</div>';
                        
                    // add source link form area
                    $taggingTab .= '<div id="link-input-container" class="collapse">';
                            $taggingTab .= '<div>';
                                $taggingTab .= "<span>Link:</span><br/>";
                            $taggingTab .= '</div>';
                            
                            $taggingTab .= '<div id="link-url-input">';
                                $taggingTab .= '<input class="saving-link-test" type="url" name="" placeholder="Enter URL here">';
                            $taggingTab .= '</div>';

                            $taggingTab .= '<div id="link-description-input">';
                                $taggingTab .= '<label>Additional description:</label><br/>';
                                $taggingTab .= '<textarea rows= "3" class="saving-link-test" type="text" placeholder="" name=""></textarea>';
                            $taggingTab .= '</div>';

                            $taggingTab .= "<button type='submit' class='theme-color-background' id='link-save-button' 
                                                onClick='saveLink(".$itemData['ItemId'].", ".get_current_user_id()."
                                                , \"".$statusTypes[1]['ColorCode']."\", ".sizeof($progressData).")'>";
                                $taggingTab .= "SAVE";
                            $taggingTab .= "</button>";
                            $taggingTab .= '<div id="item-link-spinner-container" class="spinner-container spinner-container-left">';
                                $taggingTab .= '<div class="spinner"></div>';
                            $taggingTab .= "</div>";
                            $taggingTab .= '<div style="clear:both;"></div>';
                    $taggingTab .=    "</div>";

          $taggingTab .= '<div id="item-link-list" class="item-data-output-list">';
                    $taggingTab .= '<ul>';
                        foreach ($itemData['Properties'] as $property) {
                            if ($property['PropertyDescription'] != "NULL") {
                                $description = $property['PropertyDescription'];
                            }
                            else {
                                $description = "";
                            } 

                            if ($property['PropertyType'] == "Link") {
                                $taggingTab .= '<li id="link-'.$property['PropertyId'].'">';
                                    $taggingTab .= '<div id="link-data-output-'.$property['PropertyId'].'" class="">';
                                        $taggingTab .= '<div id="link-data-output-display-'.$property['PropertyId'].'" class="link-data-output-content">';
                                                
                                            $taggingTab .= '<div class="item-data-output-element-header">';
                                                $taggingTab .= '<a href="'.$property['PropertyValue'].'" target="_blank">';
                                                        $taggingTab .= $property['PropertyValue'];
                                                $taggingTab .= '</a>';
                                                $taggingTab .= '<i class="edit-item-data-icon fas fa-pencil theme-color-hover" 
                                                                onClick="editLinksource('.$property['PropertyId'].')"></i>';
                                                $taggingTab .= '<i class="edit-item-data-icon delete-item-data fas fa-trash-alt theme-color-hover" 
                                                                onClick="deleteItemData(\'Properties\', '.$property['PropertyId'].', '.$_GET['item'].', \'link\')"></i>';
                                                $taggingTab .= '<div style="clear:both;"></div>';
                                            $taggingTab .= '</div>';
                                            $taggingTab .= '<div>';
                                                $taggingTab .= '<span>';
                                                    $taggingTab .= 'Description: ';
                                                    $taggingTab .= $description;
                                                $taggingTab .= '</span>';
                                            $taggingTab .= '</div>';
                                        $taggingTab .= '</div>';
                            
                                        $taggingTab .= '<div class="link-data-edit-container" id="link-data-edit-'.$property['PropertyId'].'">';
                                            $taggingTab .= '<div>';
                                                $taggingTab .= "<span>Link:</span><br/>";
                                            $taggingTab .= '</div>';
                                            
                                            $taggingTab .= '<div id="link-url-input">';
                                                $taggingTab .= '<input class="saving-link-test" type="url" placeholder="Enter URL here">';
                                            $taggingTab .= '</div>';

                                            $taggingTab .= '<div id="link-description-input">';
                                                $taggingTab .= '<label>Additional description:</label><br/>';
                                                $taggingTab .= '<textarea rows= "3" class="saving-link-test" type="text" placeholder="" name=""></textarea>';
                                            $taggingTab .= '</div>';
                                            
                                            $taggingTab .= "<button type='submit' class='theme-color-background' id='link-save-button'>";
                                                $taggingTab .= "SAVE";
                                            $taggingTab .= "</button>";

                                            $taggingTab .= "<button id='save-personinfo-button' class='theme-color-background person-edit-data-cancel' onClick='editLinksource(".$property['PropertyId'].")'>";
                                                $taggingTab .= "CANCEL";
                                            $taggingTab .= "</button>";

                                            $taggingTab .= '<div id="item-link-spinner-container" class="spinner-container spinner-container-left">';
                                                $taggingTab .= '<div class="spinner"></div>';
                                            $taggingTab .= "</div>";

                                            $taggingTab .= '<div style="clear:both;"></div>';
                                        $taggingTab .= '</div>';
                                    $taggingTab .= '</div>';
                                    $taggingTab .= '</li>';
                                }
                            }
                        $taggingTab .= '</ul>';
                    $taggingTab .= '</div>';
                $taggingTab .= '</div>';
            $taggingTab .= '</div>';

            $taggingTab .= '<hr>';

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
                            $commentSection .= "<a href=\"".wp_logout_url(home_url())."\">";
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
            $i = 0;
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
                    $content .= "<a href='".home_url()."/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."' class='slider-current-item'>";
                        $content .= "<div class='slider-current-item-pointer'></div>";
                        $content .= "<div class='label-img-status shadow-img-corner'></div>";
                        $content .= "<div class='label-img-status' 
                                        style='border-color: ".$item['CompletionStatusColorCode']." transparent transparent ".$item['CompletionStatusColorCode']."'></div>";
                        $content .= "<div class='image-numbering theme-color-background'>";
                            $content .= ($i + 1);
                        $content .= "</div>";

                        $content .= "<img data-lazy='".$imageLink."'>";
                    $content .= "</a>";
                    $initialSlide = $i;
                    $i++;
                }
                else {
                    $content .= "<a href='".home_url()."/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."'>";
                        $content .= "<div class='label-img-status shadow-img-corner'></div>";
                        $content .= "<div class='label-img-status' 
                                        style='border-color: ".$item['CompletionStatusColorCode']." transparent transparent ".$item['CompletionStatusColorCode']."'></div>";
                        $content .= "<div class='image-numbering theme-color-background'>";
                            $content .= ($i + 1);
                        $content .= "</div>";
                        
                        $content .= "<img data-lazy='".$imageLink."'>";
                        
                    $content .= "</a>";
                    $i++;
                }
            }

        $content .= "</div>";

// Image slider JavaScript
$infinite = "true";
if (sizeof($storyData['Items']) > 100) {
$infinite = "false";
}
$content .= "<script>
            var width = window.innerWidth;
            var slidesToShow = 3;
            if (width > 520) {
                slidesToShow = 4;
            }
            if (width > 670) {
                slidesToShow = 5;
            }
            if (width > 820) {
                slidesToShow = 6;
            }
            if (width > 970) {
                slidesToShow = 7;
            }
            if (width > 1120) {
                slidesToShow = 8;
            }
            if (width > 1270) {
                slidesToShow = 9;
            }
            if (width > 1420) {
                slidesToShow = 10;
            }
            if (width > 1570) {
                slidesToShow = 11;
            }
            if (width > 1720) {
                slidesToShow = 12;
            }
            if (width > 1920) {
                slidesToShow = 13;
            }
            var initialSlide = ".$initialSlide." - Math.floor(slidesToShow / 2);
            if (initialSlide < 1) {
                initialSlide = 0;
            }
            jQuery('.item-page-slider').slick({
                dots: true,
                arrows: true,
                infinite: ".$infinite.",
                speed: 300,
                slidesToShow: 13,
                slidesToScroll: 13,
                lazyLoad: 'ondemand',
                initialSlide: initialSlide,
                responsive: [
                    {
                        breakpoint: 1920,
                        settings: { slidesToShow: 12, slidesToScroll: 12, }
                    },
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
    </script>";

            $content .= '<div class="item-navigation-area">';
                $content .= '<ul class="item-navigation-content-container left" style="">';
                    $content .= '<li><a href="'.home_url().'/documents" style="text-decoration:none;">Stories</a></li>';
                    $content .= '<li><i class="fal fa-angle-right"></i></li>';
                    $content .= '<li><span style="text-decoration:none;">';
                        $content .= '<a href="'.home_url().'/documents/story?story='.$itemData['StoryId'].'">';
                            $content .= $storyData['dcTitle'];
                        $content .= '</a>';
                    $content .= '</span></li>';
                    /*$content .= '<li><i class="fal fa-angle-right"></i></li>';
                    $content .= '<li><span>item number</span></li>';*/
                $content .= '</ul>';
                $content .= '<ul class="item-navigation-content-container right" style="">';
                    $content .= '<li><a title="first" href=""><i class="fal fa-angle-double-left"></i></a></li>';
                    $content .=  '<li class="rgt"><a title="previous" href=""><i class="fal fa-angle-left"></i></a></li>';
                    $content .=  '<li class="rgt">';
                        $content .= '<a title="Story:'.$storyData['dcTitle'].'" href="'.$storyData['dcTitle'].'">';
                        $content .= '<i class="fal fa-book"></i></a>';
                    $content .= '</li>';
                    $content .= '<li class="rgt"><a title="next" href=""><i class="fal fa-angle-right"></i></a></li>';
                    $content .= '<li class="rgt"><a title="last"  href=""><i class="fal fa-angle-double-right"></i></a></li>';
                $content .= '</ul>';
            $content .= '</div>';   
            $content .= "<div class='primary-full-width'>";
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

                    $content .= "<div id='full-view-info'>";
                        $content .= $infoTab;
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
                    $url = home_url()."/tp-api/completionStatus";
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
                    $content .= '<div class="view-switcher" id="switcher-casephase">';
                        $content .= '<ul id="item-switch-list" class="switch-list">';

                            $content .= "<li>";
                                $content .= '<i id="popout" class="far fa-window-restore fa-rotate-180 view-switcher-icons"
                            onclick="switchItemView(event, \'popout\')"></i>';
                            $content .= "</li>";

                            $content .= "<li>";
                                $content .= '<i id="vertical-split" class="far fa-window-maximize fa-rotate-180 view-switcher-icons"
                            onclick="switchItemView(event, \'vertical\')"></i>';
                            $content .= "</li>";

                            $content .= "<li>";
                                $content .= '<i id="horizontal-split" class="far fa-window-maximize fa-rotate-90 view-switcher-icons active theme-color" style="font-size:12px;"
                            onclick="switchItemView(event, \'horizontal\')"></i>';
                            $content .= "</li>";

                            $content .= "<li>";
                                $content .= '<i id="horizontal-split" class="fas fa-times view-switcher-icons"
                            onclick="switchItemView(event, \'closewindow\')"></i>';
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
	$content .= "<div id='all-places-map'></div>";
	$content .= "<script>
jQuery(document).ready(function() {
    var mapAll = new mapboxgl.Map({
      container: 'all-places-map',
      style: 'mapbox://styles/fandf/cjnq53ido0nlt2smrm8wjd8nw', // replace this with your style URL
      center: [18, 46],
      zoom: 2.5
    });

    var data = [
  {
    'PlaceId': 7,
    'Name': 'test location',
    'Latitude': 10,
    'Longitude': 10,
    'ItemId': 384783,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 19,
    'Name': 'Heidelberg',
    'Latitude': 49.4056,
    'Longitude': 8.54353,
    'ItemId': 384782,
    'Link': '',
    'Zoom': 10,
    'Comment': 'Uni',
    'UserGenerated': '1',
    'UserId': 1
  },
  {
    'PlaceId': 20,
    'Name': 'eedfdsggregfdxfd',
    'Latitude': 15,
    'Longitude': 16,
    'ItemId': 384786,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 21,
    'Name': 'eedfdsggregfdxfd',
    'Latitude': 15,
    'Longitude': 16,
    'ItemId': 384786,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 22,
    'Name': 'hkjjkjkl',
    'Latitude': 15,
    'Longitude': 12,
    'ItemId': 370157,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 23,
    'Name': 'fdsfads',
    'Latitude': 15,
    'Longitude': 16,
    'ItemId': 370157,
    'Link': '',
    'Zoom': 10,
    'Comment': 'vvcbetrr',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 24,
    'Name': 'dasdasd',
    'Latitude': 15,
    'Longitude': 52.21,
    'ItemId': 370162,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 25,
    'Name': 'fdgdfg',
    'Latitude': 1,
    'Longitude': 2,
    'ItemId': 370162,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 26,
    'Name': 'fdgdfg',
    'Latitude': 1,
    'Longitude': 52,
    'ItemId': 370162,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 27,
    'Name': 'fdgdfg',
    'Latitude': 1,
    'Longitude': 52,
    'ItemId': 370162,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 28,
    'Name': 'fdsfdsd',
    'Latitude': 15,
    'Longitude': 12.5,
    'ItemId': 370162,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 29,
    'Name': 'fadsfsd',
    'Latitude': 12,
    'Longitude': 15,
    'ItemId': 370162,
    'Link': '',
    'Zoom': 10,
    'Comment': 'fsdafas',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 30,
    'Name': 'asdas',
    'Latitude': 15,
    'Longitude': 16,
    'ItemId': 370162,
    'Link': '',
    'Zoom': 10,
    'Comment': 'dasdas',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 31,
    'Name': 'jhgjhgj',
    'Latitude': 12,
    'Longitude': 15,
    'ItemId': 361540,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 32,
    'Name': 'hgfhgfhfg',
    'Latitude': 12,
    'Longitude': 13,
    'ItemId': 361540,
    'Link': '',
    'Zoom': 10,
    'Comment': '',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 35,
    'Name': 'vbxvcbxc',
    'Latitude': 12,
    'Longitude': 16,
    'ItemId': 336287,
    'Link': '',
    'Zoom': 10,
    'Comment': 'hgfhfgh',
    'UserGenerated': '1',
    'UserId': 1
  },
  {
    'PlaceId': 36,
    'Name': 'fdsafsdfas',
    'Latitude': 16,
    'Longitude': 18,
    'ItemId': 336287,
    'Link': '',
    'Zoom': 10,
    'Comment': 'gfdsgdfg',
    'UserGenerated': '1',
    'UserId': 1
  },
  {
    'PlaceId': 37,
    'Name': 'gsfgfsd',
    'Latitude': 12,
    'Longitude': 15,
    'ItemId': 314478,
    'Link': '',
    'Zoom': 10,
    'Comment': 'gfdsgsfdgfd',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 38,
    'Name': 'dasdasddas',
    'Latitude': 13,
    'Longitude': 15,
    'ItemId': 314478,
    'Link': '',
    'Zoom': 10,
    'Comment': 'ggggdsf',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 39,
    'Name': 'bnbvcnbvcnbvcn',
    'Latitude': 13,
    'Longitude': 16,
    'ItemId': 314478,
    'Link': '',
    'Zoom': 10,
    'Comment': 'qweqeqw',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 41,
    'Name': 'name',
    'Latitude': 1,
    'Longitude': 2,
    'ItemId': 314478,
    'Link': '',
    'Zoom': 10,
    'Comment': 'desc',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 43,
    'Name': 'asdfsadf',
    'Latitude': 12,
    'Longitude': 121212000,
    'ItemId': 308358,
    'Link': '',
    'Zoom': 10,
    'Comment': 'sdfsadfsadfdsf',
    'UserGenerated': '1',
    'UserId': 5
  },
  {
    'PlaceId': 44,
    'Name': 'berlin',
    'Latitude': 12,
    'Longitude': 1234,
    'ItemId': 308358,
    'Link': '',
    'Zoom': 10,
    'Comment': 'note note note',
    'UserGenerated': '1',
    'UserId': 5
  },
  {
    'PlaceId': 45,
    'Name': 'note',
    'Latitude': 12,
    'Longitude': 121,
    'ItemId': 308358,
    'Link': '',
    'Zoom': 10,
    'Comment': 'sa',
    'UserGenerated': '1',
    'UserId': 5
  },
  {
    'PlaceId': 46,
    'Name': 'dsadljksaJDKL',
    'Latitude': 16,
    'Longitude': 15,
    'ItemId': 407718,
    'Link': '',
    'Zoom': 10,
    'Comment': 'gfadsgfdsf fadslkfa',
    'UserGenerated': '1',
    'UserId': 2
  },
  {
    'PlaceId': 47,
    'Name': 'Potsdam',
    'Latitude': 12,
    'Longitude': 1212,
    'ItemId': 308358,
    'Link': '',
    'Zoom': 10,
    'Comment': 'Berlin',
    'UserGenerated': '1',
    'UserId': 5
  }
];

console.log(data);
    // convert data to geojson format
    var geojson = {type: 'FeatureCollection', features: [] }
    for(var i = 0; i < data.length; i++) {
      geojson.features.push({
        type: 'Feature',
        properties: {
          id: data[i].PaceId,
          name: data[i].Name,
          comment: data[i].Comment,
          userId: data[i].UserId
        },
        geometry: {
          type: 'Point',
          coordinates: [data[i].Latitude, data[i].Longitude]
        }
      })
    }
    console.log(geojson);

    mapAll.on('load', function() {
      mapAll.addSource('all-places', {
        type: 'geojson',
        data: geojson,
        cluster: true,
        clusterMaxZoom: 14,
        clusterRadius: 50
      });
      mapAll.addLayer({
        id: 'clusters',
        type: 'circle',
        source: 'all-places',
        filter: ['has', 'point_count'],
        paint: {
          'circle-color': [
            'step',
            ['get', 'point_count'],
            '#51bbd6',
            3,
            '#f1f075',
            5,
            '#f28cb1'
            ],
          'circle-radius': [
            'step',
            ['get', 'point_count'],
            20,
            3,
            30,
            5,
            40
          ]
        }
      })
      mapAll.addLayer({
        id: 'cluster-count',
        type: 'symbol',
        source: 'all-places',
        filter: ['has', 'point_count'],
        layout: {
          'text-field': '{point_count_abbreviated}',
          'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
          'text-size': 12
        }
      });

      mapAll.addLayer({
        id: 'unclustered-point',
        type: 'circle',
        source: 'all-places',
        filter: ['!', ['has', 'point_count']],
        paint: {
          'circle-color': '#11b4da',
          'circle-radius': 4,
          'circle-stroke-width': 1,
          'circle-stroke-color': '#fff'
        }
      });

      mapAll.on('click', 'clusters', function (e) {
        console.log('adsfasdfhsd');
        var features = mapAll.queryRenderedFeatures(e.point, { layers: ['clusters'] });
        var clusterId = features[0].properties.cluster_id;
        mapAll.getSource('all-places').getClusterExpansionZoom(clusterId, function (err, zoom) {
          if (err)
          return;

          mapAll.easeTo({
            center: features[0].geometry.coordinates,
            zoom: zoom
          });
        });
      });

      mapAll.on('mouseenter', 'clusters', function () {
        mapAll.getCanvas().style.cursor = 'pointer';
      });
      mapAll.on('mouseleave', 'clusters', function () {
        mapAll.getCanvas().style.cursor = '';
      });
    });

});

    </script>";
        echo $content;
    }
}
add_shortcode( 'item_page_test_iiif', '_TCT_item_page_test_iiif' );
?>
