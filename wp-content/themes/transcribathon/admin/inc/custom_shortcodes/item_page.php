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

                .transcription-toggle a{
                    color: inherit;
                    text-decoration: none;
                }
                .transcription-toggle:hover {
                     color: #000;
                     border-bottom: 1px solid #000;
                }

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
                            if (event.target.id != "automaticEnrichment-status-indicator") {
                                var dropdown = document.getElementById("automaticEnrichment-status-dropdown");
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
               /* $editorTab .= '<div id="item-status-changer" class="status-changer">';
                    $editorTab .= '<i id="item-status-indicator" class="fal fa-circle status-indicator"
                                        style="color: '.$itemData['CompletionStatusColorCode'].'; background-color:'.$itemData['CompletionStatusColorCode'].';"
                                        onclick="document.getElementById(\'item-status-dropdown\').classList.toggle(\'show\')"></i>';
                    $editorTab .= '<div id="item-status-dropdown" class="status-dropdown-content">';
                        foreach ($statusTypes as $statusType) {
                            if ($itemData['CompletionStatusId'] == $statusType['CompletionStatusId']) {
                                $editorTab .= "<div class='status-dropdown-option status-dropdown-option-current'
                                                    onclick=\"changeStatus(".$_GET['item'].", '".$statusType['Name']."', 'CompletionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                $editorTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                            } else {
                                $editorTab .= "<div class='status-dropdown-option'
                                                    onclick=\"changeStatus(".$_GET['item'].", '".$statusType['Name']."', 'CompletionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                $editorTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                            }
                        }
                    $editorTab .= '</div>';
                $editorTab .= '</div>';*/


                $editorTab .= '<div id="item-progress-bar">';
                    $editorTab .= '<div class="statusinfo-on-hover">';
                        $editorTab .= '<ul class="on-hover-displayer">';
                        /*
                            $editorTab .= '<li><span class="users-num"></span><span class="amount"></span><span class="pieces">0</span>users</li>';
                            $editorTab .= '<li><span class="not-yet-num"></span><span class="amount"></span><span class="pieces">8</span>Total items</li>';
                            $editorTab .= '<li><span class="complete-num"></span><span class="amount">0%</span><span class="pieces">0</span>completed</li>';
                            $editorTab .= '<li><span class="review-num"></span><span class="amount">0%</span><span class="pieces">0</span>in review</li>';
                            $editorTab .= '<li><span class="edit-num"></span><span class="amount">0%</span><span class="pieces">0</span>started</li>';
                            */
                            foreach ($statusTypes as $statusType) {
                                $percentage = ($progressCount[$statusType['Name']] / sizeof($progressData)) * 100;
                                $editorTab .= '<li><span class="users-num" style="background-color:'.$statusType['ColorCode'].'"></span><span id="progress-bar-overlay-'.str_replace(' ', '-', $statusType['Name']).'-section" class="amount" style="width: 20%;">'.$percentage.'%</span><span class="pieces">'.$statusType['Name'].'</span></li>';
                            }
                        $editorTab .= '</ul>';
                    $editorTab .= '</div>';
                    foreach ($statusTypes as $statusType) {
                        $percentage = ($progressCount[$statusType['Name']] / sizeof($progressData)) * 100;
                        if ($percentage != 0) {
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
                    $editorTab .= do_shortcode('[ultimatemember form_id="38"]');
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
                                        $editorTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                                    } else {
                                        $editorTab .= "<div class='status-dropdown-option'
                                                            onclick=\"changeStatus(".$_GET['item'].", '".$statusType['Name']."', 'TranscriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                        $editorTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
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
                $editorTab .= '<p id="item-page-current-transcription">';
                    $editorTab .= htmlspecialchars($currentTranscription);
                $editorTab .= '</p>';
                $editorTab .= '<textarea id="item-page-transcription-text" rows="4">';
                    $editorTab .= $currentTranscription;
                $editorTab .= '</textarea>';
                $editorTab .= "<button class='save-transcription theme-color-background' id='transcription-update-button' style='float: right;' onClick='updateItemTranscription(".$itemData['ItemId'].", ".get_current_user_id().")'>";
                    $editorTab .= "SAVE TRANSCRIPTION";
                    $editorTab .= '<script>
                                    function onButtonClick(){
                                        document.getElementById("textInput").className="show";
                                    }
                                </script>';
                $editorTab .= "</button>";
                $editorTab .= "<div style='clear:both'>";
                $editorTab .= "</div>";
                $editorTab .= "<span id='transcription-update-message'>";
                $editorTab .= "</span>";
            $editorTab .= '</div>';

            //$editorTab .= "<hr>";

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
                $editorTab .= '<div id="description-status-changer" class="status-changer section-status-changer">';
                    $editorTab .= '<i id="description-status-indicator" class="fal fa-circle status-indicator"
                                        style="color: '.$itemData['DescriptionStatusColorCode'].'; background-color:'.$itemData['DescriptionStatusColorCode'].';"
                                        onclick="document.getElementById(\'description-status-dropdown\').classList.toggle(\'show\')"></i>';
                    $editorTab .= '<div id="description-status-dropdown" class="sub-status status-dropdown-content">';
                        foreach ($statusTypes as $statusType) {
                            if ($itemData['DescriptionStatusId'] == $statusType['CompletionStatusId']) {
                                $editorTab .= "<div class='status-dropdown-option status-dropdown-option-current'
                                                    onclick=\"changeStatus(".$_GET['item'].", '".$statusType['Name']."', 'DescriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                $editorTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                            } else {
                                $editorTab .= "<div class='status-dropdown-option'
                                                    onclick=\"changeStatus(".$_GET['item'].", '".$statusType['Name']."', 'DescriptionStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                $editorTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                            }
                        }
                    $editorTab .= '</div>';
                $editorTab .= '</div>';
                $editorTab .= '<div style="clear: both;"></div>';
                    $editorTab .= "<div id=\"description-area\" class=\"transcription-history-area collapse show\">";
                        $editorTab .= '<label class="container">Letter<input id="type-letter-checkbox" type="checkbox" checked="checked" name="doctype" value="card"><span  class=" theme-color-background checkmark"></span></label>';
                        $editorTab .= '<label class="container">Diary<input type="checkbox" name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';
                        $editorTab .= '<label class="container">Post card<input type="checkbox"  name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';
                        $editorTab .= '<label class="container">Picture<input type="checkbox"  name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';

                        $editorTab .= '<textarea id="item-page-description-text" rows="4">';
                            $editorTab .= $itemData['Description'];
                        $editorTab .= '</textarea>';
                        $editorTab .= "<button class='theme-color-background' id='description-update-button' style='float: right;' onClick='updateItemDescription(".$itemData['ItemId'].")'>";
                            $editorTab .= "SAVE DESCRIPTION";
                        $editorTab .= "</button>";
                        $editorTab .= "<div style='clear:both'>";
                        $editorTab .= "</div>";
                        $editorTab .= "<span id='description-update-message'>";
                        $editorTab .= "</span>";
                    $editorTab .= '</div>';
                $editorTab .= '</div>';

                //$editorTab .= "<hr>";

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
                        $editorTab .= '<span class="day-n-time">'.$transcription["Timestamp"].'</span>';
                        $editorTab .= '<i class="fas fa-user-alt" style= "margin: 0 6px;"></i>';
                        $editorTab .= '<span class="day-n-time"><a href="#"> USER-NAME</a></span>';
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
                            $editorTab .= '<p id="transcription-comparison-output-'.$i.'" class="transcription-comparison-output">';
                            $editorTab .= '</p>';

                        $editorTab .= '</div>';
                    $i++;
                }
                $editorTab .= '</div>';
            $editorTab .= '</div>';

        // Info tab
        $infoTab = "";
            $infoTab .= '<div id="info-collapse-headline-container" class="item-page-section-headline-container collapse-headline item-page-section-collapse-headline collapse-controller" data-toggle="collapse" href="#info-collapsable"
            onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')
                jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')">';
                $infoTab .= '<h4 id="info-collapse-heading" class="theme-color item-page-section-headline">';
                    $infoTab .= 'DOCUMENT META DATA';
                $infoTab .= '</h4>';
                $infoTab .= '<i id="info-collapse-icon" class="far fa-caret-circle-down collapse-icon theme-color" style="font-size: 17px; float:left;  margin-right: 8px; margin-top: 9px;"></i>';
            $infoTab .= '</div>';
            $infoTab .= '<div style="clear: both;"></div>';

            $infoTab .= '<div id="info-collapsable" class="collapse">';
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
            $taggingTab .= "<div id='location-section' class='item-page-section'>";
                $taggingTab .= "<div class='item-page-section-headline-container'>";
                    $taggingTab .= "<div id='full-view-map'>";
                        $taggingTab .= '<iframe src="https://www.google.com/maps/embed?pb=" width="800" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>';
                    $taggingTab .= "</div>";
                    $taggingTab .= "<div class='add-location-button'>";
                    $taggingTab .= '<i class="fal fa-plus-circle theme-color" style="padding-right: 3px;"></i>';
                        $taggingTab .= '<a href="" style="text-decoration: none;">Add location</a>';
                    $taggingTab .= "</div>";
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
                                        $taggingTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                                    } else {
                                        $taggingTab .= "<div class='status-dropdown-option'
                                                            onclick=\"changeStatus(".$_GET['item'].",  '".$statusType['Name']."', 'LocationStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                        $taggingTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                                    }
                                }
                            $taggingTab .= '</div>';
                        $taggingTab .= '</div>';
                    $taggingTab .= '</div>';

                $taggingTab .= '</div>';
                $taggingTab .= '<div style="clear: both;"></div>';
            $taggingTab .= '</div>';
            $taggingTab .= "<div id='tagging-section' class='item-page-section'>";
                $taggingTab .= "<div class='item-page-section-headline-container'>";
                    $taggingTab .= "<h4 class='theme-color item-page-section-headline'>";
                        $taggingTab .= "TAGGING";
                    $taggingTab .= "</h4>";
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
                                        $taggingTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                                    } else {
                                        $taggingTab .= "<div class='status-dropdown-option'
                                                            onclick=\"changeStatus(".$_GET['item'].",  '".$statusType['Name']."', 'TaggingStatusId', ".$statusType['CompletionStatusId'].", '".$statusType['ColorCode']."', ".sizeof($progressData).", this)\">";
                                        $taggingTab .= "<i class='fal fa-circle' style='color: ".$statusType['ColorCode']."; background-color:".$statusType['ColorCode'].";'></i>".$statusType['Name']."</div>";
                                    }
                                }
                            $taggingTab .= '</div>';
                        $taggingTab .= '</div>';
                    $taggingTab .= '</div>';
                $taggingTab .= '</div>';
                $taggingTab .= '<div style="clear: both;"></div>';
            $taggingTab .= '</div>';

        // Help tab
        $helpTab = "";
            $helpTab .= "<p>test... help tab</p>";

        // Automatic enrichment tab
        $autoEnrichmentTab = "";
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
            $autoEnrichmentTab .= "</div>";

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
                        $commentSection .= "<textarea id=\"comment\" rows=\"3\" name=\"comment\" aria-required=\"true\">";
                        $commentSection .= "</textarea>";
                        $commentSection .= "<input name=\"wpml_language_code\" type=\"hidden\" value=\"en\" />";
                        $commentSection .= "<p class=\"form-submit\">";
                            $commentSection .= "<input name=\"submit\" type=\"submit\" id=\"submit\" class=\"submit theme-color-background\" value=\"SAVE NOTE\" />";
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
        $content .= "<button id='item-page-switcher' onclick='switchItemPageView()'>switch</button>";

        // <<< FULL VIEW >>> //

        $content .= "<div id='full-view-container'>";
            // Top image slider
            $content .= "<div class='item-page-slider full-width-header'>";
            $i = 1;
                foreach ($storyData['Items'] as $item) {
                    $image = json_decode($item['ImageLink'], true);
                    $link = explode("/", $image["@id"]);
                    $link[sizeof($link) - 3] = "150,150";
                    if ($image["width"] <= $image["height"]) {
                        $link[sizeof($link) - 4] = "0,0,".$image["width"].",".$image["width"];
                    }
                    else {
                        $link[sizeof($link) - 4] = "0,0,".$image["height"].",".$image["height"];
                    }
                    $item['ImageLink'] = "";
                    foreach ($link as $text) {
                        $item['ImageLink'] .= $text .= "/";
                    }
                    $item['ImageLink'] = substr($item['ImageLink'], 0, -1);
                    if ($initialSlide == null && $item['ItemId'] == $_GET['item']){
                        $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."' style='border: 4px #949494 solid'>";
                            $content .= "<img data-lazy='".$item['ImageLink']."'>";
                        $content .= "</a>";
                        $initialSlide = $i;
                    }
                    else {
                        $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."'>";
                            $content .= "<img data-lazy='".$item['ImageLink']."'>";
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
$content .= "<script>
            jQuery(document).ready(function(){
                jQuery('.item-page-slider').slick({
                    dots: true,
                    infinite: true,
                    arrows: false,
                    speed: 300,
                    slidesToShow: 12,
                    slidesToScroll: 12,
                    lazyLoad: 'ondemand',
                    centerMode: true,
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
                                $content .= '<i class="fal fa-map-marker-alt" style="margin-left: -9px;"></i>';
                                $content .= '<i class="fal fa-tag" style="position: absolute; left: 9px; top: 1px;"></i>';
                            $content .= "</div>";
                        $content .= "</li>";

                        $content .= "<li>";
                            $content .= "<div class='theme-color theme-color-hover tablinks'
                                            onclick='switchItemTab(event, \"autoEnrichment-tab\")'>";
                                $content .= '<i class="fal fa-laptop" style="margin-left: -3px;"></i>';
                            $content .= "</div>";
                        $content .= "</li>";

                        $content .= "<li>";
                            $content .= "<div class='theme-color theme-color-hover tablinks'
                                            onclick='switchItemTab(event, \"info-tab\")'>";
                                $content .= '<i class="fal fa-info-circle"></i>';
                            $content .= "</div>";
                        $content .= "</li>";

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


                                $content .= "<div class='mini-mini-chart'>";
                                    $content .= "<canvas id='statusChart' class='status-mini-chart' style='display:inline;' width='38' height='38'>";
                                    $content .= "</canvas>";
                                    $content .= '<div class="statusinfo-hover">';
                                        $content .= '<ul class="hover-displayer">';

                                            foreach ($statusTypes as $statusType) {
                                                $percentage = ($progressCount[$statusType['Name']] / sizeof($progressData)) * 100;
                                                $content .= '<li><span class="users-num" style="background-color:'.$statusType['ColorCode'].'; color:'.$statusType['ColorCode'].'"></span><span id="progress-doughnut-overlay-'.str_replace(' ', '-', $statusType['Name']).'-section" class="amount" style="width: 20%;">'.$percentage.'%</span><span class="pieces">'.$statusType['Name'].'</span></li>';
                                            }
                                        $content .= '</ul>';
                                    $content .= '</div>';
                                $content .= "</div>";

                                $content .= "<script>
                                                var ctx = document.getElementById('statusChart');
                                                var myDoughnutChart = new Chart(ctx, {
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
                                                    var labels = myDoughnutChart.data.labels;
                                                    for (var i = 0; i < labels.length; i++){
                                                        if (labels[i] == oldStatusName) {
                                                            var oldStatusIndex = i;
                                                        }
                                                        if (labels[i] == newStatusName) {
                                                            var newStatusIndex = i;
                                                        }
                                                    }

                                                    myDoughnutChart.data.datasets[0].data[oldStatusIndex] -= 1;
                                                    myDoughnutChart.update();
                                                    myDoughnutChart.data.datasets[0].data[newStatusIndex] += 1;
                                                    myDoughnutChart.update();
                                                }
                                            </script>";


                    //////////////////////////////////////////////////////////////////////////////////////
                    // View switcher
                    $content .= '<div class="view-switcher">';
                        $content .= '<ul id="item-switch-list" class="switch-list">';

                            $content .= "<li>";
                                $content .= '<i id="horizontal-split" class="far fa-window-maximize fa-rotate-270 theme-color-hover theme-color view-switcher-icons active"
                            onclick="switchItemView(event, \'horizontal\')"></i>';
                            $content .= "</li>";

                            $content .= "<li>";
                                $content .= '<i id="vertical-split" class="far fa-window-maximize theme-color-hover theme-color view-switcher-icons"
                            onclick="switchItemView(event, \'vertical\')"></i>';
                            $content .= "</li>";

                            $content .= "<li>";
                                $content .= '<i id="popout" class="far fa-expand-arrows theme-color-hover theme-color view-switcher-icons"
                            onclick="switchItemView(event, \'popout\')"></i>';
                            $content .= "</li>";

                            /*$content .= "<li>";
                                $content .= '<i id="horizontal-split" class="fal fa-window-close theme-color-hover theme-color view-switcher-icons"
                            onclick="switchItemView(event, \'horizontal\')"></i>';
                            $content .= "</li>";*/

                        $content .= '</ul>';
                    $content .= '</div>';
                $content .= "</div>";

                // Tab content
                $content .= "<div id='item-data-content' class='panel-right-tab-menu'>";

                    // Editor tab
                    $content .= "<div id='editor-tab' class='tabcontent'>";
                        // Content will be added here in switchItemPageView function
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
