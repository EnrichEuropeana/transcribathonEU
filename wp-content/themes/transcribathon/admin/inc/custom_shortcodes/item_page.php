<?php
/*
Shortcode: item_page
Description: Gets item data and builds the item page
*/

// include required files
include($_SERVER["DOCUMENT_ROOT"].'/wp-load.php');

function _TCT_item_page( $atts ) {
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
              $imageViewer .= '<div id="transcribe locked"><i class="far fa-lock"></i></div>';
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

                $editorTab .= '<div id="no-text-container">';
                    $editorTab .= '<div style="display: -webkit-inline-box;">';
                        $editorTab .= '<input type="checkbox" id="no-text-checkbox">';
                        $editorTab .= '<label class="theme-color" id="no-text-label" for="no-text-checkbox">';
                            $editorTab .= 'Nothing to transcribe';
                        $editorTab .= '</label>';
                        $editorTab .= '<p>';
                            $editorTab .= 'No Text:';
                        $editorTab .= '</p>';
                        $editorTab .= '<label class="switch-notext-mark">';
                            $editorTab .= '<input type="checkbox">';
                            $editorTab .= '<span class="slider round"></span>';
                        $editorTab .= '</label>';
                        $editorTab .= '<div style="clear:both;"></div>';
                    $editorTab .= '</div>';
                $editorTab .= '</div>';

                $editorTab .= "<script>
                                    jQuery('#no-text-checkbox').change(function() {
                                        if(this.checked) {
                                            jQuery('#no-text-label').addClass('theme-color-background');
                                            jQuery('#no-text-label').removeClass('theme-color');
                                        }
                                        else {
                                            jQuery('#no-text-label').removeClass('theme-color-background');
                                            jQuery('#no-text-label').addClass('theme-color');
                                        }
                                    })
                                </script>";
                
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
                    /*$editorTab .= '<div id="item-page-transcription-text">';
                        $editorTab .= $currentTranscription;
                    $editorTab .= '</div>';
                    $editorTab .= "<script>
                                    tinymce.init({
                                        selector: '#item-page-transcription-text',
                                        inline: true
                                    });
                                </script>";*/

                    // Set request parameters for language data
                    $url = network_home_url()."/tp-api/languages";
                    $requestType = "GET";

                    // Execude http request
                    include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                    // Save language data
                    $languages = json_decode($result, true);

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
                    $editorTab .= "<button class='save-transcription theme-color-background' id='transcription-update-button' style='float: right;' onClick='updateItemTranscription(".$itemData['ItemId'].", 5)'>";
                        $editorTab .= "SAVE"; // save transcription
                    $editorTab .= "</button>";
                    $editorTab .= "<script>                                     
                                        jQuery('#item-page-transcription-text').keyup(function() {
                                        var block_data = jQuery(this).html();
                                                if(block_data.length==0){
                                                jQuery('#transcription-update-button').css('display','none');
                                                }else{
                                            jQuery('#transcription-update-button').css('display','block');
                                            }
                                        });
                                    </script>";
                    $editorTab .= "<div style='clear:both'></div>";
                    $editorTab .= "<span id='transcription-update-message'></span>";
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
                    $editorTab .= "<div id=\"description-area\" class=\"description-save collapse show\">";

                        // Set request parameters for category data
                        $url = network_home_url()."/tp-api/properties?PropertyType='Category'";
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
                            $editorTab .= '<label class="category-checkbox-container">';
                                $editorTab .= $category['PropertyValue'];
                                $editorTab .= '<input class="category-checkbox" id="type-'.$category['PropertyValue'].'-checkbox" type="checkbox" '.$checked.' 
                                                    name="'.$category['PropertyValue'].'"value="'.$category['PropertyId'].'"
                                                    onClick="addItemProperty('.$_GET['item'].', this)">';
                                $editorTab .= '<span  class="theme-color-background checkmark"></span>';
                            $editorTab .= '</label>';
                        }

                        $editorTab .= '<textarea id="item-page-description-text" rows="4">';
                            if ($itemData['Description'] != null) {
                                $editorTab .= $itemData['Description'];
                            }
                        $editorTab .= '</textarea>';
                        
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
            $infoTab .= '<div id="info-collapse-headline-container" class="item-page-section-headline-container">';
                $infoTab .= '<h4 id="info-collapse-heading" class="theme-color item-page-section-headline">';
                    $infoTab .= 'Additional Information';
                $infoTab .= '</h4>';
                $infoTab .= '<i class="fal fa-info-square theme-color" style="font-size: 17px; float:left;  margin-right: 8px; margin-top: 9.6px;"></i>';
            $infoTab .= '</div>';
            $infoTab .= '<div style="clear: both;"></div>';

            $infoTab .= '<div>';
                $infoTab .= "<h4 class='theme-color item-page-section-headline'>";
                    $infoTab .= "Title: ".$itemData['Title'];
                $infoTab .= "</h4>";
                $infoTab .= "<p class='item-page-property-value'>";
                    $infoTab .= $itemData['Description'];
                $infoTab .= "</p>";

                $infoTab .= "<h5 class='theme-color item-page-property-headline'>";
                    $infoTab .= "People";
                $infoTab .= "</h5>";
                $infoTab .= "<p class='item-page-property'>";
                    $infoTab .= "<span class='item-page-property-key'>";
                        $infoTab .= "Contributor: ";
                    $infoTab .= "</span>";
                    $infoTab .= "<span class='item-page-property-value'>";
                        $infoTab .= $itemData['Contributor'];
                    $infoTab .= "</span>";
                $infoTab .= "</p>";
                $infoTab .= "<p class='item-page-property'>";
                    $infoTab .= "<span class='item-page-property-key'>";
                        $infoTab .= "Subject: ";
                    $infoTab .= "</span>";
                    $infoTab .= "<span class='item-page-property-value'>";
                        $infoTab .= $itemData['StoryPlaceName'];
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
                        $infoTab .= $itemData['Title'];
                    $infoTab .= "</span>";
                $infoTab .= "</p>";
                $infoTab .= "<p class='item-page-property'>";
                    $infoTab .= "<span class='item-page-property-key'>";
                        $infoTab .= "Subject: ";
                    $infoTab .= "</span>";
                    $infoTab .= "<span class='item-page-property-value'>";
                        $infoTab .= $itemData['StoryPlaceName'];
                    $infoTab .= "</span>";
                $infoTab .= "</p>";

                $infoTab .= "<h5 class='theme-color item-page-property-headline'>";
                    $infoTab .= "Properties";
                $infoTab .= "</h5>";
                $infoTab .= "<p class='item-page-property'>";
                    $infoTab .= "<span class='item-page-property-key'>";
                        $infoTab .= "Language: ";
                    $infoTab .= "</span>";
                    $infoTab .= "<span class='item-page-property-value'>";
                        $infoTab .= $itemData['Languauges'][0];
                    $infoTab .= "</span>";
                $infoTab .= "</p>";
                $infoTab .= "<p class='item-page-property'>";
                    $infoTab .= "<span class='item-page-property-key'>";
                        $infoTab .= "Keyword: ";
                    $infoTab .= "</span>";
                    $infoTab .= "<span class='item-page-property-value'>";
                        $infoTab .= $itemData['SearchText'];
                    $infoTab .= "</span></br>";
                $infoTab .= "</p>";
                $infoTab .= "<p class='item-page-property'>";
                    $infoTab .= "<span class='item-page-property-key'>";
                        $infoTab .= "Link: ";
                    $infoTab .= "</span>";
                    $infoTab .= "<span class='item-page-property-value'>";
                        $infoTab .= $itemData['Link'];
                    $infoTab .= "</span></br>";
                $infoTab .= "</p>";
                $infoTab .= "<p class='item-page-property'>";
                    $infoTab .= "<span class='item-page-property-key'>";
                        $infoTab .= "Category: ";
                    $infoTab .= "</span>";
                    $infoTab .= "<span class='item-page-property-value'>";
                        $infoTab .= $itemData['Title'];
                    $infoTab .= "</span></br>";
                $infoTab .= "</p>";

                // Just filler content for now, to make the size realistic
                $infoTab .= "<p class='theme-color item-page-property-headline'>Time</p>";
                $infoTab .= "<span class='item-page-property-key'>Creation date: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['Timestamp']."</span></br>";

                /*
                $infoTab .= "<div class='provenance-metadata'>";
                    $infoTab .= "<p class='theme-color item-page-property-headline'>Provenance</p>";
                    $infoTab .= "<table>";
                        $infoTab .= "<tr>";
                        $infoTab .= "<td class='item-page-property-key'>"; $infoTab .= "Source:"; $infoTab .= "</td>";
                        $infoTab .= "<td class='item-page-property-value'>"; $infoTab .= "DA-Plakate Ehrliche Arbeit und Wolfgang Schnur"; $infoTab .= "</td>";
                        $infoTab .= "</tr>";
                        $infoTab .= "<tr>";
                        $infoTab .= "<td class='item-page-property-key'>"; $infoTab .= "Provenance:"; $infoTab .= "</td>";
                        $infoTab .= "<td class='item-page-property-value'>"; $infoTab .= "DA-Plakate Ehrliche Arbeit und Wolfgang Schnur"; $infoTab .= "</td>";
                        $infoTab .= "</tr>";
                        $infoTab .= "<tr>";
                        $infoTab .= "<td class='item-page-property-key'>"; $infoTab .= "Identifier:"; $infoTab .= "</td>";
                        $infoTab .= "<td class='item-page-property-value'>"; $infoTab .= "DA-Plakate Ehrliche Arbeit und Wolfgang Schnur"; $infoTab .= "</td>";
                        $infoTab .= "</tr>";
                        $infoTab .= "<tr>";
                        $infoTab .= "<td class='item-page-property-key'>"; $infoTab .= "Institution:"; $infoTab .= "</td>";
                        $infoTab .= "<td class='item-page-property-value'>"; $infoTab .= "DA-Plakate Ehrliche Arbeit und Wolfgang Schnur"; $infoTab .= "</td>";
                        $infoTab .= "</tr>";
                        $infoTab .= "<tr>";
                        $infoTab .= "<td class='item-page-property-key'>"; $infoTab .= "Provider:"; $infoTab .= "</td>";
                        $infoTab .= "<td class='item-page-property-value'>"; $infoTab .= "DA-Plakate Ehrliche Arbeit und Wolfgang Schnur"; $infoTab .= "</td>";
                        $infoTab .= "</tr>";
                        $infoTab .= "<tr>";
                        $infoTab .= "<td class='item-page-property-key'>"; $infoTab .= "Providing country:"; $infoTab .= "</td>";
                        $infoTab .= "<td class='item-page-property-value'>"; $infoTab .= "DA-Plakate Ehrliche Arbeit und Wolfgang Schnur"; $infoTab .= "</td>";
                        $infoTab .= "</tr>";
                        $infoTab .= "<tr>";
                        $infoTab .= "<td class='item-page-property-key'>"; $infoTab .= "First published in Europeana:"; $infoTab .= "</td>";
                        $infoTab .= "<td class='item-page-property-value'>"; $infoTab .= "DA-Plakate Ehrliche Arbeit und Wolfgang Schnur"; $infoTab .= "</td>";
                        $infoTab .= "</tr>";
                        $infoTab .= "<tr>";
                        $infoTab .= "<td class='item-page-property-key'>"; $infoTab .= "Last updated in Europeana:"; $infoTab .= "</td>";
                        $infoTab .= "<td class='item-page-property-value'>"; $infoTab .= "DA-Plakate Ehrliche Arbeit und Wolfgang Schnur"; $infoTab .= "</td>";
                        $infoTab .= "</tr>";
                    $infoTab .= "</table>";
                $infoTab .= "</div>";
*/

                $infoTab .= "<p class='theme-color item-page-property-headline'>Provenance</p>";
                $infoTab .= "<span class='item-page-property-key'>Source: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['Title']."</span></br>";
                $infoTab .= "<span class='item-page-property-key'>Provenance: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['Title']."</span></br>";
                $infoTab .= "<span class='item-page-property-key'>Identifier: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['Title']."</span></br>";
                $infoTab .= "<span class='item-page-property-key'>Institution: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['Title']."</span></br>";
                $infoTab .= "<span class='item-page-property-key'>Provider: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['Title']."</span></br>";
                $infoTab .= "<span class='item-page-property-key'>Providing country: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['Title']."</span></br>";
                $infoTab .= "<span class='item-page-property-key'>First published in Europeana: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['DateStart']."</span></br>";
                $infoTab .= "<span class='item-page-property-key'>Last updated in Europeana: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['DateEnd']."</span></br>";

                $infoTab .= "<p class='theme-color item-page-property-headline'>References and relations</p>";
                $infoTab .= "<span class='item-page-property-key'>Location: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['TranscriptionId']."</span></br>";

                $infoTab .= "<p class='theme-color item-page-property-headline'>Location</p>";
                $infoTab .= "<span class='item-page-property-key'>Dataset: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['PlaceId']."</span></br>";

                $infoTab .= "<p class='theme-color item-page-property-headline'>Entities</p>";
                $infoTab .= "<span class='item-page-property-key'>Concept term: </span>";
                $infoTab .= "<span class='item-page-property-value'>".$itemData['ImageLink']."</span></br>";
            $infoTab .= "</div>";

        // Tagging tab
        $taggingTab = "";
                    $taggingTab .= "<div id='full-view-map'>";
                        $taggingTab .= '<iframe src="https://www.google.com/maps/embed?pb=" width="800" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>';
                    $taggingTab .= "</div>";
                $taggingTab .= "<div class='item-page-section-headline-container'>";
                            // Location section
                    $taggingTab .= "<div id='location-section' class='item-page-section'>";
                        $taggingTab .= "<i class='fal fa-map-marker-alt theme-color' style='padding-right: 3px; font-size: 17px; margin-right:8px;'></i>";
                        $taggingTab .= "<h4 class='theme-color item-page-section-headline'>";
                            $taggingTab .= "Location";
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

                    


                    $taggingTab .= '<div class="add-location-button">';
                        $taggingTab .= '<div class= "collapse-headline collapse-controller collapsed" data-toggle="collapse" href="#modalocation"
                                    onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')
                                    jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')">';
                        // Trigger/Open The Modal
                        $taggingTab .= '<span id="adding-location-here" class="theme-color" href="#">';
                        $taggingTab .= '<i class="far fa-plus-circle" style="margin-right:4px;"></i>';
                        $taggingTab .= 'add Location';
                        $taggingTab .= '</span>';
                        $taggingTab .= '</div>';
                                // The Modal 
                                    $taggingTab .= '<div class="modalocation-content item-map-modal" id="modalocation">';
                                        $taggingTab .= '<form action="/action_page.php">';
                                        
                                            $taggingTab .= '<div class="location-common location-detail-intro-line">';
                                                $taggingTab .= "<p>Add main location to</p>";
                                                /*$taggingTab .= '<span class="close">&times;</span>';*/
                                            $taggingTab .= '</div>';

                                            $taggingTab .= '<div class="location-detil-entry">';
                                            $taggingTab .= '<label for="location-detail">Location name:</label><br/>';
                                            $taggingTab .= '<input type="text" id="location-detail" name="" placeholder="">';
                                        $taggingTab .= '</div>';

                                            $taggingTab .= '<div class="location-common">';
                                            
                                                $taggingTab .= '<div class="location-detail-entry" style="margin-right:4em;">';
                                                    $taggingTab .=    '<label for="co-ordinate">Coordinates:</label><br/>';
                                                    $taggingTab .=    '<input type="text" id="co-ordinate" name="" placeholder="">';
                                                $taggingTab .= '</div>';

                                                $taggingTab .= '<div class="location-detail-entry">';
                                                    $taggingTab .=    '<label for="zommer">Zoom:</label><br/>';
                                                    $taggingTab .=    '<input type="text" id="zoomer" name="" placeholder="">';
                                                $taggingTab .= '</div>';
                                            $taggingTab .= '</div>';

                                            $taggingTab .= '<div class="location-common location-description-look">';
                                                $taggingTab .= '<form class="location-desc" action="/action_page.php">';
                                                    $taggingTab .=    '<label for="ldsc">Description (enter here):</label><br/>';
                                                    $taggingTab .= '<textarea rows= "2" style="resize:none;" class="gsearch-form" type="text" id="ldsc" placeholder="" name="">';
                                                    $taggingTab .= '</textarea>';
                                                    $taggingTab .= '<a class="gsearch-press" href="" theme-color-background"><i class="far fa-search" style="font-size: 10px;"></i></a>';
                                                $taggingTab .= '</form>';
                                            $taggingTab .= '</div>';

                                            $taggingTab .= '<div class="location-common location-geo-names">';
                                                $taggingTab .= '<form class="location-gn" action="/action_page.php">';
                                                    $taggingTab .=    '<label for="lgns">Search Geonames (enter details):</label><br/>';
                                                    $taggingTab .= '<input class="geosearch-form" type="text" id="lgns" placeholder="" name="">';
                                                    $taggingTab .= '<select class="geosearch-form" style="padding: 2px; outline:none;" name=""title="">
                                                                            <option value="0">all countries</option>
                                                                            <option value="1">Deutschland</option>
                                                                            <option value="2">France</option>
                                                                            <option value="3">spain</option>
                                                                            <option value="4">Italy</option>
                                                                            <option value="5">Other</option>
                                                                        </select>';
                                                    $taggingTab .= '<a class="geosearch-press" href=""><i class="far fa-search" style="font-size: 10px;"></i></a>';
                                                $taggingTab .= '</form>';
                                            $taggingTab .= '</div>';

                                            $taggingTab .= '<div class="location-common location-google-search">';
                                                $taggingTab .= '<form class="location-gs" action="/action_page.php">';
                                                    $taggingTab .=    '<label for="lgs">Search Google (enter address):</label><br/>';
                                                    $taggingTab .= '<input class="gsearch-form" type="text" id="lgs" placeholder="" name="">';
                                                    $taggingTab .= '<a class="gsearch-press" href="" theme-color-background"><i class="far fa-search" style="font-size: 10px;"></i></a>';
                                                $taggingTab .= '</form>';
                                            $taggingTab .= '</div>';

                                            $taggingTab .= "<div>";
                                                    $taggingTab .= "<button class='save-transcription theme-color-background' id='location-update-button' style='float: right;' onClick='updateItemTranscription(".$itemData['ItemId'].", ".get_current_user_id().")'>";
                                                    $taggingTab .= "SAVE LOCATION";
                                                    $taggingTab .= '<script>
                                                                    function onButtonClick(){
                                                                        document.getElementById("textInput").className="show";
                                                                    }
                                                                </script>';
                                                $taggingTab .= "</button>";
                                            $taggingTab .= "</div>";
                                            $taggingTab .= "<div style='clear:both;'></div>";
                                            
                                        $taggingTab .= '</form>';
                                    $taggingTab .=    "</div>";
                                        // popup script
                                        
                                        /*$taggingTab .= '<script>
                                                        // Get the modal
                                                        var modal = document.getElementById("mylocationhere");
                                                        
                                                        // Get the button that opens the modal
                                                        var btn = document.getElementById("adding-location-here");
                                                        
                                                        // Get the <span> element that closes the modal
                                                        var span = document.getElementsByClassName("close")[0];
                                                        
                                                        // When the user clicks the button, open the modal 
                                                        btn.onclick = function() {
                                                        modal.style.display = "block";
                                                        }
                                                        
                                                        // When the user clicks on <span> (x), close the modal
                                                        span.onclick = function() {
                                                        modal.style.display = "none";
                                                        }
                                                        
                                                    
                                                        jQuery("#modalocation").draggable({
                                                            handle: ".location-detail-intro-line"
                                                        });
                                                        jQuery( "#modalocation" ).resizable({ handles: "n, e, s, w, se, ne, sw, nw" })

                                                        </script>';*/
                    $taggingTab .= "</div>";
                $taggingTab .= '</div>';

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
                    //$taggingTab .= '<div style="clear: both;"></div>';
                $taggingTab .= '</div>';
                //$taggingTab .= '<div style="clear: both;"></div>';
                $taggingTab .= '<div>';
                    $taggingTab .= '<p>';
                        $taggingTab .= '<span>';
                            $taggingTab .= 'Document date';
                        $taggingTab .= '</span>';
                    $taggingTab .= '</p>';
                        $taggingTab .= '<input type="text" id="startdateentry" name="" placeholder="Start Date: dd/mm/yyyy" style="outline:none;"><i class="far fa-calendar-check" style= "margin: 0 6px;"></i>';
                        $taggingTab .= '<input type="text" id="enddateentry" name="" placeholder="End Date: dd/mm/yyyy" style="outline:none; margin-left: 4px;"><i class="fas fa-calendar-alt" style= "margin-left: 6px;"></i>';                                  
                        $taggingTab .= '<div style="clear:both;"></div>';
                $taggingTab .= '</div>';
                               $taggingTab .= '<script>
                                                    jQuery( "#startdateentry, #enddateentry" ).datepicker({
                                                    dateFormat: "dd/mm/yy",
                                                    changeMonth: true,
                                                    changeYear: true,
                                                    yearRange: "1000:2019"
                                                    });
                                                </script>';
                $taggingTab .= '<div class="person-info-area">';                    
                    $taggingTab .= '<div>';
                        $taggingTab .= '<p>';
                            $taggingTab .= '<span>';
                                $taggingTab .= 'Person';
                            $taggingTab .= '</span>';
                        $taggingTab .= '</p>';
                    $taggingTab .= '</div>';
                    $taggingTab .= '<div id="person-entry-detail">';
                        $taggingTab .= '<input type="text" id="person-entry" name="" placeholder="First Name" style="outline:none;">';
                        $taggingTab .= '<input type="text" id="person-descript" name="" placeholder="Last Name" style="outline:none; margin-left: 4px;">';
                    $taggingTab .= '</div>'; 
                    $taggingTab .= '<div>';
                        $taggingTab .= '<input type="text" id="dob-entry" name="" placeholder="Birth: dd/mm/yyyy" style="outline:none;"><i class="fas fa-calendar-day" style= "margin:0 6px;"></i>';
                        $taggingTab .= '<input type="text" id="dod-entry" name="" placeholder="Death: dd/mm/yyyy" style="outline:none; margin-left: 4px;"><i class="fas fa-calendar-day" style= "margin-left: 6px;"></i>';
                        //$taggingTab .= '<input type="submit" value="+" class="theme-color-background" onclick="#" style="outline:none; padding: 6px; margin-left: 4px; padding-bottom: 3.5px;">';
                        $taggingTab .= '<script>
                                        jQuery( function() {
                                            jQuery( "#dob-entry, #dod-entry" ).datepicker({
                                            dateFormat: "dd/mm/yy",
                                            changeMonth: true,
                                            changeYear: true,
                                            yearRange: "1000:2019"
                                            });
                                        } );
                                        </script>';
                    $taggingTab .= '</div>';    
                        $taggingTab .= '<div style="clear:both;"></div>';
                $taggingTab .= '</div>';

                $taggingTab .= '<div>';
                    //$taggingTab .= '<p><span>Keywords:</span></p>';
                        $taggingTab .= '<div>';
                            $taggingTab .= '<label for="keyword">';
                                $taggingTab .= 'Keywords:';
                            $taggingTab .= '</label></br>';
                        $taggingTab .= '</div>';
                        $taggingTab .= '<div>';
                            $taggingTab .= '<input type="text" id="keyword" name="" placeholder="" style="outline:none;">';
                            $taggingTab .= '<input type="submit" value="+" class="theme-color-background" onclick="#" style="outline:none; padding: 6px; margin-left: 4px; padding-bottom: 3.5px;">';
                        $taggingTab .= '</div>';
                $taggingTab .= '</div>';
                $taggingTab .= '<div>';
                    $taggingTab .= '<p>';
                        $taggingTab .= '<span>';
                            $taggingTab .= 'Other sources';
                        $taggingTab .= '</span>';
                    $taggingTab .= '</p>';
                    /////
                            // Trigger/Open The Modal
                        $taggingTab .= '<div class= "collapse-headline collapse-controller collapsed" data-toggle="collapse" href="#modalsource-content"
                            onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')
                            jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')">';
                            $taggingTab .= '<a href="#" id="adding-new-link" class="theme-color" href="#">';
                            $taggingTab .= '<i class="far fa-plus-circle" style="margin-right:4px;"></i>';
                            $taggingTab .= 'Add a link';
                            $taggingTab .= '</a>';
                        $taggingTab .= '</div>';                            
                                $taggingTab .= '<div id="modalsource-content">';
                                    $taggingTab .= '<form action="/action_page.php">';
                                                    
                                        $taggingTab .= '<div>';
                                            $taggingTab .= "<p style='float:left;'>Additional information on</p><br/><span style='float:left;'>Title</span>";
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
        $content .= "<div class='item-page-slider full-width-header test-width'>";
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
                    $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."' class='slider-storyitem-pointer'>";
                        $content .= "<img data-lazy='".$imageLink."'>";
                    $content .= "</a>";
                    $initialSlide = $i;
                }
                else {
                    $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."'>";
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
                arrows: false,
                infinite: ".$infinite.",
                speed: 300,
                slidesToShow: 13,
                slidesToScroll: 13,
                lazyLoad: 'ondemand',
                initialSlide: ".$initialSlide.",
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
        });
    </script>";

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
            $content .= '<div id="openseadragonFS">  <div class="buttons" id="buttonsFS">';
            $content .= '<div id="zoom-inFS"><i class="far fa-plus"></i></div>';
            $content .= '<div id="zoom-outFS"><i class="far fa-minus"></i></div>';
            $content .= '<div id="homeFS"><i class="far fa-home"></i></div>';
            $content .= '<div id="full-widthFS"><i class="far fa-arrows-alt-h"></i></div>';
            $content .= '<div id="rotate-rightFS"><i class="far fa-redo"></i></div>';
            $content .= '<div id="rotate-leftFS"><i class="far fa-undo"></i></div>';
            $content .= '<div id="filterButtonFS"><i class="far fa-sliders-h"></i></div>';
            $content .= '<div id="full-pageFS"><i class="far fa-compress-arrows-alt"></i></div>';
            $content .= '</div></div>';
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
add_shortcode( 'item_page', '_TCT_item_page' );
?>
