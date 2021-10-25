<?php
$theme_sets = get_theme_mods();

/* Set up facet fields and labels */

$storyFacetFields = [
    [
        "fieldName" => "CompletionStatus",
        "fieldLabel" => "COMPLETION STATUS"],
    [
        "fieldName" => "edmLanguage",
        "fieldLabel" => "LANGUAGE"],
    [
        "fieldName" => "dcLanguage",
        "fieldLabel" => "DOCUMENT LANGUAGE"],
    [
        "fieldName" => "Categories",
        "fieldLabel" => "DOCUMENT TYPE"],
    [
        "fieldName" => "edmCountry",
        "fieldLabel" => "PROVIDING COUNTRY"],
    [
        "fieldName" => "Dataset",
        "fieldLabel" => "DATASET"],
    [
        "fieldName" => "edmProvider",
        "fieldLabel" => "AGGREGATOR"]
];

$itemFacetFields = [
    [
        "fieldName" => "CompletionStatus",
        "fieldLabel" => "COMPLETION STATUS"],
    [
        "fieldName" => "Categories",
        "fieldLabel" => "DOCUMENT TYPE"],
    [
        "fieldName" => "Languages",
        "fieldLabel" => "LANGUAGES"]
];


$itemPage = $_GET['pi'];
$storyPage = $_GET['ps'];

 // #### Story Solr request start #### 

 // Build query from url parameters
 $url = 'http://transcribathon.eu:8983/solr/Stories/select?facet=on';

 foreach ($storyFacetFields as $storyFacetField) {
    $url .= '&facet.field='.urlencode($storyFacetField['fieldName']);
 }

 $url .= '&q=';
 if ($_GET['qs'] != null && $_GET['qs'] != "") {
    $url .= 'dcDescription:("'.urlencode($_GET['qs']).'")+OR+';
    $url .= 'StoryId:("'.urlencode($_GET['qs']).'")+OR+';
    $url .= 'dcTitle:("'.urlencode($_GET['qs']).'")';
 }
 else {
    $url .= '*:*';
 }

 $url .= '&fq=';

 $url .= "(ProjectId:\"".get_current_blog_id()."\")";
 $first = true;
 for ($j = 0; $j < sizeof($storyFacetFields); $j++) {
    for ($i = 0; $i < sizeof($_GET[$storyFacetFields[$j]['fieldName']]); $i++) {
        if ($first == true) {
            $url .= "+AND+";
            $first = false;
        }
        if ($i == 0) {
            $url .= "(";
        }
        $url .= urlencode($storyFacetFields[$j]['fieldName']).':"'.str_replace(" ", "+", urlencode($_GET[$storyFacetFields[$j]['fieldName']][$i])).'"';
        if (($i + 1) < sizeof($_GET[$storyFacetFields[$j]['fieldName']])) {
            $url .= "+OR+";
        }
        else {
            $url .= ")";
            if (($j + 1) < sizeof($storyFacetFields)) {
                for ($k = ($j + 1); $k < sizeof($storyFacetFields); $k++) {
                    if (sizeof($_GET[$storyFacetFields[$k]['fieldName']]) > 0) {
                        $url .= "+AND+";
                        break;
                    }
                }
            }
        }
    }
 }
 if ($storyPage != null && is_numeric($storyPage) && $storyPage != 0){
    $url .= "&rows=24&start=".(($storyPage - 1) * 24);
 }
 else {
    $url .= "&rows=24&start=0";
 }
 $url .= "&sort=StoryId%20desc";
 $requestType = "GET";

 include get_stylesheet_directory() . '/admin/inc/custom_scripts/send_api_request.php';

 $solrStoryData = json_decode($result, true);
 
 $storyCount = $solrStoryData['response']['numFound'];
 
 if ($storyPage != null && is_numeric($storyPage) && (($storyPage - 1) * 24) < $storyCount && $storyPage != 0){
     $storyStart = (($storyPage - 1) * 24) + 1;
     $storyEnd = $storyPage * 24;
 }
 else {
     $storyPage = 1;
     $storyStart = 1;
     $storyEnd = 24;
 }

 // #### Story Solr request end ####


 // #### Item Solr request start ####

 // Build query from url parameters
 $url = 'http://transcribathon.eu:8983/solr/Items/select?facet=on';

 foreach ($itemFacetFields as $itemFacetField) {
    $url .= '&facet.field='.$itemFacetField['fieldName'];
 }

 $url .= '&q=';
 if ($_GET['qi'] != null && $_GET['qi'] != "") {
    $url .= 'TranscriptionText:('.urlencode($_GET['qi']).')+OR+';
    $url .= 'Description:('.urlencode($_GET['qi']).')+OR+';
    $url .= 'ItemId:('.urlencode($_GET['qi']).')+OR+';
    $url .= 'Title:('.urlencode($_GET['qi']).')';
 }
 else {
    $url .= '*:*';
 }
 $url .= '&fq=';

 for ($j = 0; $j < sizeof($itemFacetFields); $j++) {
    for ($i = 0; $i < sizeof($_GET[$itemFacetFields[$j]['fieldName']]); $i++) {
        if ($i == 0) {
            $url .= "(";
        }
        $url .= urlencode($itemFacetFields[$j]['fieldName']).':"'.str_replace(" ", "+", urlencode($_GET[$itemFacetFields[$j]['fieldName']][$i])).'"';
        if ($i + 1 < sizeof($_GET[$itemFacetFields[$j]['fieldName']])) {
            $url .= "+OR+";
        }
        else {
            $url .= ")";
            if (($j + 1) < sizeof($itemFacetFields) && sizeof($_GET[$itemFacetFields[$j+1]['fieldName']]) > 0) {
                for ($k = ($j + 1); $k < sizeof($itemFacetFields); $k++) {
                    if (sizeof($_GET[$itemFacetFields[$k]['fieldName']]) > 0) {
                        $url .= "+AND+";
                        break;
                    }
                }
            }
        }
    }
 }
 
 if ($itemPage != null && is_numeric($itemPage) && $itemPage != 0){
    $url .= "&rows=24&start=".(($itemPage - 1) * 24);
 }
 else {
    $url .= "&rows=24&start=0";
 }
 $url .= "&sort=Timestamp%20desc";

 $requestType = "GET";

 include get_stylesheet_directory() . '/admin/inc/custom_scripts/send_api_request.php';

 $solrItemData = json_decode($result, true);

 $itemCount = $solrItemData['response']['numFound'];
 
 if ($itemPage != null && is_numeric($itemPage) && (($itemPage - 1) * 24) < $itemCount && $itemPage != 0){
     $itemStart = (($itemPage - 1) * 24) + 1;
     $itemEnd = $itemPage * 24;
 }
 else {
     $itemPage = 1;
     $itemStart = 1;
     $itemEnd = 24;
 }

 // #### Item Solr request end ####
 


// Get status data
$url = home_url()."/tp-api/completionStatus";
$requestType = "GET";

include dirname(__FILE__)."/../../custom_scripts/send_api_request.php";

// Save status data
$statusTypes = json_decode($result, true);

 $content = "";

 $content .= "<style>
                 .search-bar input::placeholder {
                     color: ".$theme_sets['vantage_general_link_color'].";
                 }
             </style>";

// Show grid view as default
$view = "grid";

if (isset($_GET['view']) && $_GET['view'] != "") {
    $view = $_GET['view'];
}

$content .= '<script>
                jQuery ( document ).ready(function() {';
                    if ($view == "list") {
                        $content .= 'jQuery(".search-results-list-radio").click()';
                    }
    $content .= '});
            </script>';

$itemTabContent = "";
$storyTabContent = "";

$clearButton = "";
if (isset($_GET['qs']) || isset($_GET['qi']))  {
    $clearButton .= "<a style='text-decoration:none; outline:none;' href='dev/documents'>clear filters</a>";
}


    // #### Header Search Start ####

    $storyTabContent .= '<section class="temp-back">';
        $storyTabContent .= '<div class="facet-form-search">';
            $storyTabContent .= '<div><input class="search-field" type="text" placeholder="Add a search term" name="qs" form="story-facet-form" value='.$_GET['qs'].'></div>';
            $storyTabContent .= '<div><button type="submit" form="story-facet-form" class="theme-color-background document-search-button"><i class="far fa-search" style="font-size: 20px;"></i></button></div>';
            $storyTabContent .= '<div class="map-search-page"><a href="dev/map" target="_blank" form="" class="theme-color-background document-search-button"><i class="fal fa-globe-europe" style="font-size: 20px;"></i></a></div>';
            $storyTabContent .= '<div style="clear:both;"></div>';
        $storyTabContent .= '</div>';
    $storyTabContent .= '</section>';

    $itemTabContent .= '<section class="temp-back">';
        $itemTabContent .= '<div class="facet-form-search">';
            $itemTabContent .= '<div><input class="search-field" type="text" placeholder="Add a search term" name="qi" form="item-facet-form" value='.$_GET['qi'].'></div>';
            $itemTabContent .= '<div><button type="submit" form="item-facet-form" class="theme-color-background document-search-button"><i class="far fa-search" style="font-size: 20px;"></i></button></div>';
            $itemTabContent .= '<div class="map-search-page"><a href="dev/map" target="_blank" form="" class="theme-color-background document-search-button"><i class="fal fa-globe-europe" style="font-size: 20px;"></i></a></div>';
            $itemTabContent .= '<div style="clear:both;"></div>';
        $itemTabContent .= '</div>';
    $itemTabContent .= '</section>';
    
    // #### Header Search End ####

        $storyTabContent .= "<div class='primary-full-width'>";
            $storyTabContent .= '<div class="complete-search-content">';

        $itemTabContent .= "<div class='primary-full-width'>";
            $itemTabContent .= '<div class="complete-search-content">';

            // #### Facets Start ####
            $storyTabContent .= '<div class="search-page-mobile-facets">';
                $storyTabContent .= '<i class="fas fa-bars"></i>';
            $storyTabContent .= '</div>';
            $storyTabContent .= '<div id="story-search-container" class="search-content-left">';
                $storyTabContent .= '<h2 class="theme-color">REFINE YOUR SEARCH <i class="facet-close-button fa fa-times"></i></h2>';

                // Item/Story switcher
                $storyTabContent .= '<div class="search-page-tab-container">';
                    $storyTabContent .= '<ul class="content-view-bar">';
                        $storyTabContent .= '<li>';
                            $storyTabContent .= '<button class="search-page-tab-button left search-page-story-tab-button theme-color-background">';
                                $storyTabContent .= 'STORIES';
                            $storyTabContent .= '</button>';
                        $storyTabContent .= '</li>';
                        $storyTabContent .= '<li>';
                            $storyTabContent .= '<button class="search-page-tab-button right search-page-item-tab-button">';
                                $storyTabContent .= 'ITEMS';
                            $storyTabContent .= '</button>';
                        $storyTabContent .= '</li>';
                    $storyTabContent .= '</ul>';
                $storyTabContent .= '</div>'; 

                // Facet form
                $storyTabContent .= '<form id="story-facet-form">';
                    $storyTabContent .= $clearButton;
                    foreach ($storyFacetFields as $storyFacetField) {
                        $facetData = $solrStoryData['facet_counts']['facet_fields'][$storyFacetField['fieldName']];
                        
                        $isEmpty = true;
                        for ($i = 0; $i < sizeof($facetData); $i = $i + 2) {
                            if ($facetData[$i + 1] != 0) {
                                $isEmpty = false;
                            }
                        }

                        if ($isEmpty != true) {
                            $storyTabContent .= '<div class="search-panel-default collapse-controller">';
                                $storyTabContent .= '<div class="search-panel-heading collapse-headline clickable" data-toggle="collapse" href="#story-'.$storyFacetField['fieldName'].'-area" 
                                                    onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')
                                                            jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')">';
                                    $storyTabContent .= '<h4 class="left-panel-dropdown-title">';  
                                        $storyTabContent .= '<li style="font-size:14px;">'.$storyFacetField['fieldLabel'].'</li>';
                                    $storyTabContent .= '</h4>';
                                    $storyTabContent .= '<i class="far fa-caret-circle-up collapse-icon theme-color" style="font-size: 17px; float:right; margin-top:17.4px;"></i>';
                                $storyTabContent .= '</div>';
    
                                $storyTabContent .= "<div id='story-".$storyFacetField['fieldName']."-area' class=\"facet-search-subsection collapse show\">";
                                    $rowCount = 0;
                                    for ($i = 0; $i < sizeof($facetData); $i = $i + 2) {
                                        if ($facetData[$i+1] != 0) {
                                            if ($rowCount == 5) {
                                                $storyTabContent .= '<div class="show-more theme-color" 
                                                                            data-toggle="collapse" href="#story-'.$storyFacetField['fieldName'].'-hidden-area">';
                                                    $storyTabContent .= 'Show More';
                                                $storyTabContent .= '</div>';

                                                $storyTabContent .= "<div id='story-".$storyFacetField['fieldName']."-hidden-area' 
                                                                        class=\"facet-search-subsection collapse\">";
                                            }
                                            $storyTabContent .= '<label class="search-container theme-color">';
                                                $storyTabContent .= $facetData[$i].' ('.$facetData[$i+1].')';
                                                $checked = "";
                                                if (isset($_GET[$storyFacetField['fieldName']]) && in_array($facetData[$i], $_GET[$storyFacetField['fieldName']])) {
                                                    $checked = "checked";
                                                }
                                                $storyTabContent .= '<input type="checkbox" name="'.$storyFacetField['fieldName'].'[]" value="'.$facetData[$i].'"
                                                                '.$checked.' onChange="this.form.submit()">
                                                                <span class="theme-color-background checkmark"></span>';
                                            $storyTabContent .= '</label>';
                                            $rowCount += 1;
                                        }
                                    }
                                    if ($rowCount > 5) {
                                            $storyTabContent .= '<div class="show-less theme-color" data-toggle="collapse" href="#story-'.$storyFacetField['fieldName'].'-hidden-area">';
                                                $storyTabContent .= 'Show Less';
                                            $storyTabContent .= '</div>';
                                        $storyTabContent .= '</div>';
                                    }
                                $storyTabContent .= '</div>';
                            $storyTabContent .= '</div>';
                        }
                    }
                $storyTabContent .= '</form>';
            $storyTabContent .= '</div>';

            $itemTabContent .= '<div class="search-page-mobile-facets">';
                $itemTabContent .= '<i class="fas fa-bars"></i>';
            $itemTabContent .= '</div>';
            $itemTabContent .= '<div class="search-content-left">';
                $itemTabContent .= '<h2 class="theme-color">REFINE YOUR SEARCH <i class="facet-close-button fa fa-times"></i></h2>';

                // Item/Story switcher
                $itemTabContent .= '<div class="search-page-tab-container">';
                    $itemTabContent .= '<ul class="content-view-bar">';
                        $itemTabContent .= '<li>';
                            $itemTabContent .= '<button class="search-page-tab-button left search-page-story-tab-button">';
                                $itemTabContent .= 'STORIES';
                            $itemTabContent .= '</button>';
                        $itemTabContent .= '</li>';
                        $itemTabContent .= '<li>';
                            $itemTabContent .= '<button class="search-page-tab-button right search-page-item-tab-button theme-color-background">';
                                $itemTabContent .= 'ITEMS';
                            $itemTabContent .= '</button>';
                        $itemTabContent .= '</li>';
                    $itemTabContent .= '</ul>';
                $itemTabContent .= '</div>';

                // Facet form
                $itemTabContent .= '<form id="item-facet-form">';
                    $itemTabContent .= $clearButton;
                    foreach ($itemFacetFields as $itemFacetField) {
                        $facetData = $solrItemData['facet_counts']['facet_fields'][$itemFacetField['fieldName']];
                        if (sizeof($facetData) > 0) {
                            $itemTabContent .= '<div class="search-panel-default collapse-controller">';
                                $itemTabContent .= '<div class="search-panel-heading collapse-headline clickable" data-toggle="collapse" href="#item-'.$itemFacetField['fieldName'].'-area" 
                                                    onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')
                                                            jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')">';
                                    $itemTabContent .= '<h4 class="left-panel-dropdown-title">';  
                                        $itemTabContent .= '<li style="font-size:14px;">'.$itemFacetField['fieldLabel'].'</li>';
                                    $itemTabContent .= '</h4>';
                                    $itemTabContent .= '<i class="far fa-caret-circle-up collapse-icon theme-color" style="font-size: 17px; float:right; margin-top:17.4px;"></i>';
                                $itemTabContent .= '</div>';
    
                                $itemTabContent .= "<div id='item-".$itemFacetField['fieldName']."-area' class=\"facet-search-subsection collapse show\">";
                                $rowCount = 0;
                                    for ($i = 0; $i < sizeof($facetData); $i = $i + 2) {
                                        if ($facetData[$i+1] != 0) {
                                            if ($rowCount == 5) {
                                                $itemTabContent .= '<div class="show-more theme-color" 
                                                                            data-toggle="collapse" href="#item-'.$itemFacetField['fieldName'].'-hidden-area">';
                                                    $itemTabContent .= 'Show More';
                                                $itemTabContent .= '</div>';
                
                                                $itemTabContent .= "<div id='item-".$itemFacetField['fieldName']."-hidden-area' 
                                                                        class=\"facet-search-subsection collapse\">";
                                            }
                                            $itemTabContent .= '<label class="search-container theme-color">';
                                                $itemTabContent .= $facetData[$i].' ('.$facetData[$i+1].')';
                                                $checked = "";
                                                if (isset($_GET[$itemFacetField['fieldName']]) && in_array($facetData[$i], $_GET[$itemFacetField['fieldName']])) {
                                                    $checked = "checked";
                                                }
                                                $itemTabContent .= '<input type="checkbox" name="'.$itemFacetField['fieldName'].'[]" value="'.$facetData[$i].'"
                                                                '.$checked.' onChange="this.form.submit()">
                                                                <span class="theme-color-background checkmark"></span>';
                                            $itemTabContent .= '</label>';
                                            $rowCount += 1;
                                        }
                                    }
                                    if ($rowCount > 5) {
                                            $itemTabContent .= '<div class="show-less theme-color" data-toggle="collapse" href="#item-'.$itemFacetField['fieldName'].'-hidden-area">';
                                                $itemTabContent .= 'Show Less';
                                            $itemTabContent .= '</div>';
                                        $itemTabContent .= '</div>';
                                    }
                                $itemTabContent .= '</div>';
                            $itemTabContent .= '</div>';
                        }
                    }
                $itemTabContent .= '</form>';
            $itemTabContent .= '</div>';

            // #### Facets End ####


            // #### Results Start ####

            $storyTabContent .= '<div class="search-content-right">';
                $storyTabContent .= '<div class="search-content-right-header">';
                    
                    // List/Grid switcher
                    $storyTabContent .= '<div class="search-content-results-headline search-content-results-view search-division-detail">';
                        $storyTabContent .= '<div class="result-viewtype">';
                            $storyTabContent .= '<ul class="content-view-bar">';
                                $storyTabContent .= '<li class="search-results-grid-radio search-results-radio left">';
                                    $storyTabContent .= '<input id="story-grid-button" type="radio" name="view" form="story-facet-form" value="grid" checked>';
                                        $storyTabContent .= '<label for="story-grid-button" class="theme-color-background">';
                                            $storyTabContent .= '<i class="far fa-th-large" style="font-size: 12px; padding-right: 6px;"></i>';
                                            $storyTabContent .= 'Grid';
                                        $storyTabContent .= '</label>';
                                    $storyTabContent .= '</input>';
                                $storyTabContent .= '</li>';
                                $storyTabContent .= '<li class="search-results-list-radio search-results-radio right">';
                                    $storyTabContent .= '<input id="story-list-button" type="radio" name="view" form="story-facet-form" value="list">';
                                        $storyTabContent .= '<label for="story-list-button">';
                                            $storyTabContent .= '<i class="far fa-th-list theme-color" style="font-size: 12px; padding-right: 6px;"></i>';
                                            $storyTabContent .= 'List';
                                        $storyTabContent .= '</label>';
                                    $storyTabContent .= '</input>';
                                $storyTabContent .= '</li>';
                            $storyTabContent .= '</ul>';
                        $storyTabContent .= '</div>';
                    $storyTabContent .= '</div>';

                    $storyTabContent .= '<div class="search-content-results-headline search-headline">';
                        $storyTabContent .= $storyStart.' - '.$storyEnd.' of '.$storyCount.' results';
                    $storyTabContent .= '</div>';
                $storyTabContent .= '</div>';
                
                // Search result pagination
                $pagination = "";
                $pagination .= '<div class="search-page-pagination">';
                    // Left arrows
                    if ($storyPage > 1) {
                        $pagination .= '<button type="submit" form="story-facet-form" name="ps" value="1" class="theme-color-hover" style="outline:none;">';
                            $pagination .= '&laquo;';
                        $pagination .= '</button>';
                    }

                    // Previous page arrow
                        if ($storyPage != null && is_numeric($storyPage) && $storyPage > 1) {
                            $pagination .= '<button type="submit" form="story-facet-form" name="ps" value="'.($storyPage - 1).'" class="theme-color-hover" style="outline:none;">';
                                $pagination .= '&lsaquo;';
                            $pagination .= '</button>';
                        }

                    // Previous page number
                        if ($storyPage != null && is_numeric($storyPage) && $storyPage > 1) {
                            $pagination .= '<button type="submit" form="story-facet-form" name="ps" value="'.($storyPage - 1).'" class="theme-color-hover" style="outline:none;">';
                                $pagination .= ($storyPage - 1);
                            $pagination .= '</button>';
                        }

                    // Current page
                        $pagination .= '<button type="submit" form="story-facet-form" name="ps" value="'.$storyPage.'" class="theme-color-background" style="outline:none;">';
                            $pagination .= $storyPage;
                        $pagination .= '</button>';

                    // 3 next pages
                    for ($i = 1; $i <= 3; $i++) {
                        if (((($storyPage + $i) - 1) * 24) < $storyCount) {
                            $pagination .= '<button type="submit" form="story-facet-form" name="ps" value="'.($storyPage + $i).'" class="theme-color-hover" style="outline:none;">';
                                $pagination .= ($storyPage + $i);
                            $pagination .= '</button>';
                        }
                    }

                    // Next page arrow
                    if ($storyPage < ceil($storyCount / 24)) {
                        $pagination .= '<button type="submit" form="story-facet-form" name="ps" value="'.($storyPage + 1).'" class="theme-color-hover" style="outline:none;">';
                            $pagination .= '&rsaquo;';  
                        $pagination .= '</button>';
                    }

                    // Right arrows
                    if ($storyPage < ceil($storyCount / 24)) {
                        $pagination .= '<button type="submit" form="story-facet-form" name="ps" value="'.ceil($storyCount / 24).'" class="theme-color-hover" style="outline:none;">';
                            $pagination .= '&raquo;';
                        $pagination .= '</button>';
                    }
                    $pagination .= '<div style="clear:both;"></div>';
                $pagination .= '</div>';

                // Pagination on top of search results
                $storyTabContent .= $pagination;

                // Search results
                $storyTabContent .= '<div class="search-content-right-items">';
                    $storyIdList = array();
                    for ($i = 0; $i < sizeof($solrStoryData['response']['docs']); $i++) {
                        array_push($storyIdList, $solrStoryData['response']['docs'][$i]['StoryId']);
                    }
                    
                    // Get additional story data
                    $url = home_url()."/tp-api/storiesMinimal?storyId=";
                    $first = true;
                    foreach($storyIdList as $storyId) {
                        if ($first == true) {
                            $first = false;
                        }
                        else {
                            $url .= ",";
                        }
                        $url .= $storyId;
                    }
                    $requestType = "GET";

                    // Execude http request
                    include dirname(__FILE__)."/../../custom_scripts/send_api_request.php";
            
                    // Save story data
                    $storyData = json_decode($result, true);


                    for ($i = 0; $i < sizeof($solrStoryData['response']['docs']); $i++) {
                        $storyTabContent .= '<div class="search-page-single-result maingridview">';


                            // Single story image
                            $storyTabContent .= '<div class="search-page-single-result-image">';
                                $image = json_decode($solrStoryData['response']['docs'][$i]['PreviewImageLink'], true);
                                
                                // Get image section in correct ratio for list and grid view
                                if (substr($image['service']['@id'], 0, 4) == "http") {
                                    $listImageLink = $image['service']['@id'];
                                }
                                else {
                                    $listImageLink = "http://".$image['service']['@id'];
                                }
                                $listImageLink .= "/full/300,/0/default.jpg";

                                if (substr($image['service']['@id'], 0, 4) == "http") {
                                    $gridImageLink = $image['service']['@id'];
                                }
                                else {
                                    $gridImageLink = "http://".$image['service']['@id'];
                                }

                                if ($image["width"] != null || $image["height"] != null) {
                                    if ($image["width"] <= ($image["height"] * 2)) {
                                        $gridImageLink .= "/0,0,".$image["width"].",".($image["width"] / 2);
                                    }
                                    else {
                                        $gridImageLink .= "/".round(($image["width"] - $image["height"]) / 2).",0,".($image["height"] * 2).",".$image["height"];
                                    }
                                }
                                else {
                                    $gridImageLink .= "/full";
                                }
                                $gridImageLink .= "/280,140/0/default.jpg";

                                $storyTabContent .= "<a class='list-view-image' style='display:none' href='".home_url( $wp->request )."/story?story=".$solrStoryData['response']['docs'][$i]['StoryId']."'>";
                                    $storyTabContent .= '<img src='.$listImageLink.'>';
                                $storyTabContent .= "</a>";
                                $storyTabContent .= "<a class='grid-view-image' href='".home_url( $wp->request )."/story?story=".$solrStoryData['response']['docs'][$i]['StoryId']."'>";
                                    $storyTabContent .= '<img src='.$gridImageLink.'>';
                                $storyTabContent .= "</a>";

                                // Progress bar

                                $statusData = array();
                                foreach ($statusTypes as $statusType) {
                                    $statusObject = new stdClass;
                                    $statusObject->Name = $statusType['Name'];
                                    $statusObject->ColorCode = $statusType['ColorCode'];
                                    $statusObject->ColorCodeGradient = $statusType['ColorCodeGradient'];
                                    $statusObject->Amount = 0;
                                    $statusObject->Percentage = 0;
                                    $statusData[$statusType['Name']] = $statusObject;
                                }
                                $itemAmount = 0;
                                $itemAmount += $solrStoryData['response']['docs'][$i]['NotStartedAmount'];
                                $itemAmount += $solrStoryData['response']['docs'][$i]['EditAmount'];
                                $itemAmount += $solrStoryData['response']['docs'][$i]['ReviewAmount'];
                                $itemAmount += $solrStoryData['response']['docs'][$i]['CompletedAmount'];
                                
                                $totalPercent = 0;

                                // Create status objects for each status
                                foreach($statusTypes as $status) {
                                    $statusObject = new stdClass;
                                    $statusObject->Name = $status['Name'];
                                    $statusObject->ColorCode = $status['ColorCode'];
                                    $statusObject->ColorCodeGradient = $status['ColorCodeGradient'];
				    switch ($status['Name']) {
					case "Not Started":
						$statusObject->Amount = $solrStoryData['response']['docs'][$i]['NotStartedAmount'];
						$statusObject->Percentage = (round($solrStoryData['response']['docs'][$i]['NotStartedAmount'] / $itemAmount, 2) * 100);
						break;
					case "Edit":
						$statusObject->Amount = $solrStoryData['response']['docs'][$i]['EditAmount'];
						$statusObject->Percentage = (round($solrStoryData['response']['docs'][$i]['EditAmount'] / $itemAmount, 2) * 100);
						break;
					case "Review":
						$statusObject->Amount = $solrStoryData['response']['docs'][$i]['ReviewAmount'];
						$statusObject->Percentage = (round($solrStoryData['response']['docs'][$i]['ReviewAmount'] / $itemAmount, 2) * 100);
						break;
					case "Completed":
						$statusObject->Amount = $solrStoryData['response']['docs'][$i]['CompletedAmount'];
                                    		$statusObject->Percentage = (round($solrStoryData['response']['docs'][$i]['CompletedAmount'] / $itemAmount, 2) * 100);
						break;
				    }

                                    $statusData[$status['Name']] = $statusObject;
                                    $totalPercent += $statusObject->Percentage;
                                }

                                // Make sure that percent total is 100
                                foreach ($statusData as $status) {
                                    if ($status->Name == "Not Started") {
                                        if ($totalPercent != 100) {
                                            $status->Amount += (100 - $totalPercent);
                                        }
                                    }
                                }                         
                                $storyTabContent .= '<div class="box-progress-bar item-status-chart">';

                                    // Status hover info box
                                    $storyTabContent .= '<div class="item-status-info-box box-status-bar-info-box">';
                                        $storyTabContent .= '<ul class="item-status-info-box-list">';
                                            foreach ($statusData as $status) {
                                                $percentage = $status->Percentage;
                                                $storyTabContent .= '<li>';
                                                    $storyTabContent .= '<span class="status-info-box-color-indicator" style="background-color:'.$status->ColorCode.';
                                                                    background-image: -webkit-gradient(linear, left top, left bottom,
                                                                    color-stop(0, '.$status->ColorCode.'), color-stop(1, '.$status->ColorCodeGradient.'));">';
                                                    $storyTabContent .= '</span>';
                                                    $storyTabContent .= '<span id="progress-bar-overlay-'.str_replace(' ', '-', $status->Name).'-section" class="status-info-box-percentage">';
                                                        $storyTabContent .= $percentage.'% | '.$status->Amount;
                                                    $storyTabContent .= '</span>';
                                                    $storyTabContent .= '<span class="status-info-box-text">';
                                                        $storyTabContent .= $status->Name;
                                                    $storyTabContent .= '</span>';
                                                $storyTabContent .= '</li>';
                                            }
                                        $storyTabContent .= '</ul>';
                                    $storyTabContent .= '</div>';

                                    $CompletedBar = "";
                                    $ReviewBar = "";
                                    $EditBar = "";
                                    $NotStartedBar = "";

                                    // Add each status section to progress bar
                                    foreach ($statusData as $status) {
                                        $percentage = $status->Percentage;

                                        switch ($status->Name) {
                                            case "Completed":
                                                $CompletedBar .= '<div id="progress-bar-'.str_replace(' ', '-', $status->Name).'-section" class="progress-bar progress-bar-section"
                                                                    style="width: '.$percentage.'%; background-color:'.$status->ColorCode.';
                                                                    ">';
                                                    $CompletedBar .= $percentage.'%';
                                                $CompletedBar .= '</div>';
                                                break;
                                            case "Review":
                                                $ReviewBar .= '<div id="progress-bar-'.str_replace(' ', '-', $status->Name).'-section" class="progress-bar progress-bar-section"
                                                                    style="width: '.$percentage.'%; background-color:'.$status->ColorCode.'">';
                                                    $ReviewBar .= $percentage.'%';
                                                $ReviewBar .= '</div>';
                                                break;
                                            case "Edit":
                                                $EditBar .= '<div id="progress-bar-'.str_replace(' ', '-', $status->Name).'-section" class="progress-bar progress-bar-section"
                                                                    style="width: '.$percentage.'%; background-color:'.$status->ColorCode.'">';
                                                    $EditBar .= $percentage.'%';
                                                $EditBar .= '</div>';
                                                break;
                                            case "Not Started":
                                                $NotStartedBar .= '<div id="progress-bar-'.str_replace(' ', '-', $status->Name).'-section" class="progress-bar progress-bar-section"
                                                                    style="width: '.$percentage.'%; background-color:'.$status->ColorCode.'">';
                                                    $NotStartedBar .= $percentage.'%';
                                                $NotStartedBar .= '</div>';
                                                break;
                                        }
                                    }
                                    if ($CompletedBar != "") {
                                        $storyTabContent .= $CompletedBar;
                                    }
                                    if ($ReviewBar != "") {
                                        $storyTabContent .= $ReviewBar;
                                    }
                                    if ($EditBar != "") {
                                        $storyTabContent .= $EditBar;
                                    }
                                    if ($NotStartedBar != "") {
                                        $storyTabContent .= $NotStartedBar;
                                    }
                                $storyTabContent .= '</div>';
                            $storyTabContent .= '</div>';

                            // Single story info
                            $storyTabContent .= '<div class="search-page-single-result-info">';
                                $storyTabContent .= '<h2 class="theme-color">';
                                    $storyTabContent .= "<a href='".home_url( $wp->request )."/story?story=".$solrStoryData['response']['docs'][$i]['StoryId']."'>";
                                        $storyTabContent .= $solrStoryData['response']['docs'][$i]['dcTitle'];
                                    $storyTabContent .= "</a>";
                                $storyTabContent .= '</h2>';
                                $storyTabContent .= '<span style="display: none">...</span>';
                            $storyTabContent .= '</div>';
                            
                            $storyTabContent .= '<div style="clear:both"></div>';
                        $storyTabContent .= '</div>';
                    }   
                $storyTabContent .= '</div>';

                // Pagination below search results
                $storyTabContent .= $pagination;
                
            $storyTabContent .= '</div>';


            
            $itemTabContent .= '<div class="search-content-right">';
                $itemTabContent .= '<div class="search-content-right-header">';

                    
                    
                    // List/Grid switcher
                    $itemTabContent .= '<div class="search-content-results-headline search-content-results-view search-division-detail">';
                        $itemTabContent .= '<div class="result-viewtype">';
                            $itemTabContent .= '<ul class="content-view-bar">';
                                $itemTabContent .= '<li class="search-results-grid-radio search-results-radio left">';
                                    $itemTabContent .= '<input id="item-grid-button" type="radio" name="view" form="item-facet-form" value="grid" checked>';
                                        $itemTabContent .= '<label for="item-grid-button" class="theme-color-background">';
                                            $itemTabContent .= '<i class="far fa-th-large" style="font-size: 12px; padding-right: 6px;"></i>';
                                            $itemTabContent .= 'Grid';
                                        $itemTabContent .= '</label>';
                                    $itemTabContent .= '</input>';
                                $itemTabContent .= '</li>';
                                $itemTabContent .= '<li class="search-results-list-radio search-results-radio right">';
                                    $itemTabContent .= '<input id="item-list-button" type="radio" name="view" form="item-facet-form" value="list">';
                                        $itemTabContent .= '<label for="item-list-button">';
                                            $itemTabContent .= '<i class="far fa-th-list theme-color" style="font-size: 12px; padding-right: 6px;"></i>';
                                            $itemTabContent .= 'List';
                                        $itemTabContent .= '</label>';
                                    $itemTabContent .= '</input>';
                                $itemTabContent .= '</li>';
                            $itemTabContent .= '</ul>';
                        $itemTabContent .= '</div>';
                    $itemTabContent .= '</div>';
                    $itemTabContent .= '<div class="search-content-results-headline search-headline">';
                        $itemTabContent .= $itemStart.' - '.$itemEnd.' of '.$itemCount.' results';
                    $itemTabContent .= '</div>';
                $itemTabContent .= '</div>';

                        
                // Search result pagination
                $pagination = "";
                $pagination .= '<div class="search-page-pagination">';
                    // Left arrows
                    if ($itemPage > 1) {
                        $pagination .= '<button type="submit" form="item-facet-form" name="pi" value="1" class="theme-color-hover" style="outline:none;">';
                            $pagination .= '&laquo;';
                        $pagination .= '</button>';
                    }

                    // Previous page
                        if ($itemPage != null && is_numeric($itemPage) && $itemPage > 1) {
                            $pagination .= '<button type="submit" form="item-facet-form" name="pi" value="'.($itemPage - 1).'" class="theme-color-hover" style="outline:none;">';
                                $pagination .= ($itemPage - 1);
                            $pagination .= '</button>';
                        }

                    // Current page
                        $pagination .= '<button type="submit" form="item-facet-form" name="pi" value="'.$itemPage.'" class="theme-color-background" style="outline:none;">';
                            $pagination .= $itemPage;
                        $pagination .= '</button>';

                    // 3 next pages
                    for ($i = 1; $i <= 3; $i++) {
                        if (((($itemPage + $i) - 1) * 24) < $itemCount) {
                            $pagination .= '<button type="submit" form="item-facet-form" name="pi" value="'.($itemPage + $i).'" class="theme-color-hover" style="outline:none;">';
                                $pagination .= ($itemPage + $i);
                            $pagination .= '</button>';
                        }
                    }

                        // Right arrows
                    if ($itemPage < ceil($itemCount / 24)) {
                        $pagination .= '<button type="submit" form="item-facet-form" name="pi" value="'.ceil($itemCount / 24).'" class="theme-color-hover" style="outline:none;">';
                            $pagination .= '&raquo;';
                        $pagination .= '</button>';
                    }
                    $pagination .= '<div style="clear:both;"></div>';
                $pagination .= '</div>';

                // Pagination on top of search results
                $itemTabContent .= $pagination;

                // Search results
                $itemTabContent .= '<div class="search-content-right-items">';
                    foreach ($solrItemData['response']['docs'] as $item) {
                        $itemTabContent .= '<div class="search-page-single-result maingridview">';

                            // Single item image
                            $itemTabContent .= '<div class="search-page-single-result-image">';
                            
                                $image = json_decode($item['PreviewImageLink'], true);
                                    
                                // Get image section in correct ratio for list and grid view
                                if (substr($image['service']['@id'], 0, 4) == "http") {
                                    $listImageLink = $image['service']['@id'];
                                }
                                else {
                                    $listImageLink = "http://".$image['service']['@id'];
                                }
                                $listImageLink .= "/full/300,/0/default.jpg";

                                if (substr($image['service']['@id'], 0, 4) == "http") {
                                    $gridImageLink = $image['service']['@id'];
                                }
                                else {
                                    $gridImageLink = "http://".$image['service']['@id'];
                                }

                                if ($image["width"] != null || $image["height"] != null) {
                                    if ($image["width"] <= ($image["height"] * 2)) {
                                        $gridImageLink .= "/0,0,".$image["width"].",".($image["width"] / 2);
                                    }
                                    else {
                                        $gridImageLink .= "/".round(($image["width"] - $image["height"]) / 2).",0,".($image["height"] * 2).",".$image["height"];
                                    }
                                }
                                else {
                                    $gridImageLink .= "/full";
                                }
                                $gridImageLink .= "/280,140/0/default.jpg";

                                $itemTabContent .= "<a class='list-view-image' style='display:none' href='".home_url( $wp->request )."/item?item=".$item['ItemId']."'>";
                                    $itemTabContent .= '<img src='.$listImageLink.'>';
                                $itemTabContent .= "</a>";
                                $itemTabContent .= "<a class='grid-view-image' href='".home_url( $wp->request )."/item?item=".$item['ItemId']."'>";
                                    $itemTabContent .= '<img src='.$gridImageLink.'>';
                                $itemTabContent .= "</a>";

                                

                                // Progress bar
                                $progressData = array(
                                    $item['TranscriptionStatus'],
                                    $item['DescriptionStatus'],
                                    $item['LocationStatus'],
                                    $item['TaggingStatus'],
                                );
                                $progressCount = array (
                                                'Not Started' => 0,
                                                'Edit' => 0,
                                                'Review' => 0,
                                                'Completed' => 0
                                            );
                                // Save each status occurence
                                foreach ($progressData as $status) {
                                    $progressCount[$status] += 1;
                                }            
                                $itemTabContent .= '<div class="box-progress-bar item-status-chart">';

                                    // Status hover info box
                                    $itemTabContent .= '<div class="item-status-info-box box-status-bar-info-box">';
                                        $itemTabContent .= '<ul class="item-status-info-box-list">';
                                            foreach ($statusTypes as $status) {
                                                $percentage = ($progressCount[$status['Name']] / sizeof($progressData)) * 100;
                                                $itemTabContent .= '<li>';
                                                    $itemTabContent .= '<span class="status-info-box-color-indicator" style="background-color:'.$status['ColorCode'].';
                                                                    background-image: -webkit-gradient(linear, left top, left bottom,
                                                                    color-stop(0, '.$status['ColorCode'].'), color-stop(1, '.$status['ColorCodeGradient'].'));">';
                                                    $itemTabContent .= '</span>';
                                                    $itemTabContent .= '<span id="progress-bar-overlay-'.str_replace(' ', '-', $status['Name']).'-section" class="status-info-box-percentage" style="width: 20%;">';
                                                        $itemTabContent .= $percentage.'%';
                                                    $itemTabContent .= '</span>';
                                                    $itemTabContent .= '<span class="status-info-box-text">';
                                                        $itemTabContent .= $status['Name'];
                                                    $itemTabContent .= '</span>';
                                                $itemTabContent .= '</li>';
                                            }
                                        $itemTabContent .= '</ul>';
                                    $itemTabContent .= '</div>';

                                    $CompletedBar = "";
                                    $ReviewBar = "";
                                    $EditBar = "";
                                    $NotStartedBar = "";

                                    // Add each status section to progress bar
                                    foreach ($statusTypes as $status) {
                                        $percentage = ($progressCount[$status['Name']] / sizeof($progressData)) * 100;

                                        switch ($status['Name']) {
                                            case "Completed":
                                                $CompletedBar .= '<div id="progress-bar-'.str_replace(' ', '-', $status['Name']).'-section" class="progress-bar progress-bar-section"
                                                                    style="width: '.$percentage.'%; background-color:'.$status->ColorCode.';
                                                                    ">';
                                                    $CompletedBar .= $percentage.'%';
                                                $CompletedBar .= '</div>';
                                                break;
                                            case "Review":
                                                $ReviewBar .= '<div id="progress-bar-'.str_replace(' ', '-', $status['Name']).'-section" class="progress-bar progress-bar-section"
                                                                    style="width: '.$percentage.'%; background-color:'.$status->ColorCode.'">';
                                                    $ReviewBar .= $percentage.'%';
                                                $ReviewBar .= '</div>';
                                                break;
                                            case "Edit":
                                                $EditBar .= '<div id="progress-bar-'.str_replace(' ', '-', $status['Name']).'-section" class="progress-bar progress-bar-section"
                                                                    style="width: '.$percentage.'%; background-color:'.$status->ColorCode.'">';
                                                    $EditBar .= $percentage.'%';
                                                $EditBar .= '</div>';
                                                break;
                                            case "Not Started":
                                                $NotStartedBar .= '<div id="progress-bar-'.str_replace(' ', '-', $status['Name']).'-section" class="progress-bar progress-bar-section"
                                                                    style="width: '.$percentage.'%; background-color:'.$status->ColorCode.'">';
                                                    $NotStartedBar .= $percentage.'%';
                                                $NotStartedBar .= '</div>';
                                                break;
                                        }
                                    }
                                    if ($CompletedBar != "") {
                                        $itemTabContent .= $CompletedBar;
                                    }
                                    if ($ReviewBar != "") {
                                        $itemTabContent .= $ReviewBar;
                                    }
                                    if ($EditBar != "") {
                                        $itemTabContent .= $EditBar;
                                    }
                                    if ($NotStartedBar != "") {
                                        $itemTabContent .= $NotStartedBar;
                                    }
                                $itemTabContent .= '</div>';
                            $itemTabContent .= '</div>';

                            // Single item info
                            $itemTabContent .= '<div class="search-page-single-result-info">';
                                $itemTabContent .= '<h2 class="theme-color">';
                                    $itemTabContent .= "<a href='".home_url( $wp->request )."/story/item?item=".$item['ItemId']."'>";
                                        $itemTabContent .= $item['Title'];
                                    $itemTabContent .= "</a>";
                                $itemTabContent .= '</h2>';
                                $itemTabContent .= '<span style="display: none">...</span>';
                            $itemTabContent .= '</div>';
                            
                            $itemTabContent .= '<div style="clear:both"></div>';
                        $itemTabContent .= '</div>';
                    }   
                $itemTabContent .= '</div>';

                // Pagination below search results
                $itemTabContent .= $pagination;

            $itemTabContent .= '</div>';

            
            // #### Results End ####


        $itemTabContent .= '</div>';
    $itemTabContent .= "</div>";

        $storyTabContent .= '</div>';
    $storyTabContent .= "</div>";

    // Show Stories unless search was done on items
    if (is_string($_GET['qi']) || $_GET['pi'] != null) { 
        $content .= '<div id="search-page-item-tab">';
            $content .= $itemTabContent;
        $content .= '</div>';
        $content .= '<div id="search-page-story-tab" style="display: none;">';
            $content .= $storyTabContent;
        $content .= '</div>';
    }
    else {
        $content .= '<div id="search-page-item-tab" style="display: none;">';
            $content .= $itemTabContent;
        $content .= '</div>';
        $content .= '<div id="search-page-story-tab">';
            $content .= $storyTabContent;
        $content .= '</div>';  
    }

echo $content;


?>