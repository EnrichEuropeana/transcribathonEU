<?php
global $wpdb;
$myid = uniqid(rand()).date('YmdHis');
$base = 0;
/* 
Description: Gets stories from the API and displays them
*/

$theme_sets = get_theme_mods();


$storyFacetFields = [
    [
        "fieldName" => "edmCountry",
        "fieldLabel" => "PROVIDING COUNTRY"],
    [
        "fieldName" => "edmProvider",
        "fieldLabel" => "AGGREGATOR"],
    [
        "fieldName" => "CompletionStatus",
        "fieldLabel" => "COMPLETION STATUS"],
    [
        "fieldName" => "edmLanguage",
        "fieldLabel" => "LANGUAGE"]
];

$itemFacetFields = [
    [
        "fieldName" => "ItemCompletionStatus",
        "fieldLabel" => "COMPLETION STATUS"],
    [
        "fieldName" => "Categories",
        "fieldLabel" => "DOCUMENT TYPE"],
    [
        "fieldName" => "Languages",
        "fieldLabel" => "LANGUAGES"],
];


$itemPage = $_GET['pi'];
$storyPage = $_GET['ps'];

 // Story Solr request start

 $url = 'http://fresenia.man.poznan.pl:8983/solr/Stories/select?facet=on';

 foreach ($storyFacetFields as $storyFacetField) {
    $url .= '&facet.field='.$storyFacetField['fieldName'];
 }

 $url .= '&q=';
 if ($_GET['qs'] != null && $_GET['qs'] != "") {
    $url .= 'dcDescription:('.$_GET['qs'].')+OR+';
    $url .= 'dcTitle:(*'.$_GET['qs'].'*)';
 }
 else {
    $url .= '*:*';
 }
 $url .= '&fq=';


 for ($j = 0; $j < sizeof($storyFacetFields); $j++) {
    for ($i = 0; $i < sizeof($_GET[$storyFacetFields[$j]['fieldName']]); $i++) {
        if ($i == 0) {
            $url .= "(";
        }
        $url .= $storyFacetFields[$j]['fieldName'].':"'.str_replace(" ", "+", $_GET[$storyFacetFields[$j]['fieldName']][$i]).'"';
        if ($i + 1 < sizeof($_GET[$storyFacetFields[$j]['fieldName']])) {
            $url .= "+OR+";
        }
        else {
            $url .= ")";
            if (($j + 1) < sizeof($storyFacetFields) && sizeof($_GET[$storyFacetFields[$j+1]['fieldName']]) > 0) {
                $url .= "+AND+";
            }
        }
    }
 }
 if ($storyPage != null && is_numeric($storyPage) && $storyPage != 0){
    $url .= "&rows=25&start=".(($storyPage - 1) * 25);
 }
 else {
    $url .= "&rows=25&start=0";
 }
 $requestType = "GET";

 include get_stylesheet_directory() . '/admin/inc/custom_scripts/send_api_request.php';

 $solrStoryData = json_decode($result, true);
 
 $storyCount = $solrStoryData['response']['numFound'];
 
 if ($storyPage != null && is_numeric($storyPage) && (($storyPage - 1) * 25) < $storyCount && $storyPage != 0){
     $storyStart = (($storyPage - 1) * 25) + 1;
     $storyEnd = $storyPage * 25;
 }
 else {
     $storyPage = 1;
     $storyStart = 1;
     $storyEnd = 25;
 }

 // Story Solr request end


 // Item Solr request start

 $url = 'http://fresenia.man.poznan.pl:8983/solr/Items/select?facet=on';

 foreach ($itemFacetFields as $itemFacetField) {
    $url .= '&facet.field='.$itemFacetField['fieldName'];
 }

 $url .= '&q=';
 if ($_GET['qi'] != null && $_GET['qi'] != "") {
    $url .= 'TranscriptionText:('.$_GET['qi'].')+OR+';
    $url .= 'Description:('.$_GET['qi'].')+OR+';
    $url .= 'Title:(*'.$_GET['qi'].'*)';
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
        $url .= $itemFacetFields[$j]['fieldName'].':"'.str_replace(" ", "+", $_GET[$itemFacetFields[$j]['fieldName']][$i]).'"';
        if ($i + 1 < sizeof($_GET[$itemFacetFields[$j]['fieldName']])) {
            $url .= "+OR+";
        }
        else {
            $url .= ")";
            if (($j + 1) < sizeof($itemFacetFields) && sizeof($_GET[$itemFacetFields[$j+1]['fieldName']]) > 0) {
                $url .= "+AND+";
            }
        }
    }
 }
 
 if ($itemPage != null && is_numeric($itemPage) && $itemPage != 0){
    $url .= "&rows=25&start=".(($itemPage - 1) * 25);
 }
 else {
    $url .= "&rows=25&start=0";
 }
 $requestType = "GET";

 include get_stylesheet_directory() . '/admin/inc/custom_scripts/send_api_request.php';

 $solrItemData = json_decode($result, true);

 $itemCount = $solrItemData['response']['numFound'];
 
 if ($itemPage != null && is_numeric($itemPage) && (($itemPage - 1) * 25) < $itemCount && $itemPage != 0){
     $itemStart = (($itemPage - 1) * 25) + 1;
     $itemEnd = $itemPage * 25;
 }
 else {
     $itemPage = 1;
     $itemStart = 1;
     $itemEnd = 25;
 }

 // Item Solr request end
 


 $content = "";

 $content .= "<style>
                 .search-bar input::placeholder {
                     color: ".$theme_sets['vantage_general_link_color'].";
                 }
             </style>";

$view = "list";

if (isset($_GET['view']) && $_GET['view'] != "") {
    $view = $_GET['view'];
}

$content .= '<script>
                jQuery ( document ).ready(function() {';
            if ($view == "grid") {
                $content .=      'jQuery(".search-results-grid-radio").click()';
            }
            $content .= '});
            </script>';

$itemTabContent = "";
$storyTabContent = "";

$content .= '<div id="story-search-container">';
    $content .=    '<div class="search-page-tab-container">';
        $content .=    '<ul class="content-view-bar">';
            if (is_string($_GET['qs']) || $_GET['ps'] != null) {
                $content .=   '<li>';
                $content .=       '<button class="search-page-tab-button search-page-item-tab-button">';
                $content .=           'ITEMS';
                $content .=       '</button>';
                $content .=   '</li>';
                $content .=     '<li>';
                $content .=         '<button class="search-page-tab-button search-page-story-tab-button theme-color-background">';
                $content .=            'STORIES';
                $content .=        '</button>';
                $content .=    '</li>';
            }
            else {
                $content .=   '<li>';
                $content .=       '<button class="search-page-tab-button search-page-item-tab-button theme-color-background">';
                $content .=           'ITEMS';
                $content .=       '</button>';
                $content .=   '</li>';
                $content .=     '<li>';
                $content .=         '<button class="search-page-tab-button search-page-story-tab-button">';
                $content .=            'STORIES';
                $content .=        '</button>';
                $content .=    '</li>';
            }
        $content .=   '</ul>';
    $content .=   '</div>';


    // Header Search Start

    $itemTabContent .= '<section class="temp-back">';
        $itemTabContent .= '<div class="facet-form-search">';;
            $itemTabContent .= '<input class="search-field" type="text" placeholder="Add a search term" name="qi" form="item-facet-form">';
            $itemTabContent .= '<button type="submit" form="item-facet-form" class="theme-color-background"><i class="far fa-search" style="font-size: 20px; float:right;"></i></button>';
        $itemTabContent .= '</div>';
    $itemTabContent .= '</section>';

    $storyTabContent .= '<section class="temp-back">';
        $storyTabContent .= '<div class="facet-form-search">';
            $storyTabContent .= '<input class="search-field" type="text" placeholder="Add a search term" name="qs" form="story-facet-form">';
            $storyTabContent .= '<button type="submit" form="story-facet-form" class="theme-color-background"><i class="far fa-search" style="font-size: 20px; float:right;"></i></button>';
        $storyTabContent .= '</div>';
    $storyTabContent .= '</section>';
    
    // Header Search End



        $itemTabContent .= "<div class='primary-full-width'>";
            $itemTabContent .= '<section class="complete-search-content">';

        $storyTabContent .= "<div class='primary-full-width'>";
            $storyTabContent .= '<section class="complete-search-content">';


            // Facets Start

            $itemTabContent .= '<div class="search-content-left">';
                $itemTabContent .= '<h2 class="theme-color">REFINE YOUR SEARCH</h2>';

                $itemTabContent .= '<form id="item-facet-form">';
                    foreach ($itemFacetFields as $itemFacetField) {
                        $itemTabContent .= '<div class="search-panel-default collapse-controller">';
                            $itemTabContent .= '<div class="search-panel-heading collapse-headline clickable" data-toggle="collapse" href="#item-'.$itemFacetField['fieldName'].'-area" 
                                                onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')
                                                        jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')">';
                                $itemTabContent .= '<h4 class="left-panel-dropdown-title">';  
                                    $itemTabContent .= '<li style="font-size:14px;">'.$itemFacetField['fieldLabel'].'</li>';
                                $itemTabContent .= '</h4>';
                                $itemTabContent .= '<i class="far fa-caret-circle-down collapse-icon theme-color" style="font-size: 17px; float:right; margin-top:17.4px;"></i>';
                            $itemTabContent .= '</div>';

                            $itemTabContent .= "<div id='item-".$itemFacetField['fieldName']."-area' class=\"facet-search-subsection collapse show\">";
                                    $facetData = $solrItemData['facet_counts']['facet_fields'][$itemFacetField['fieldName']];
                                    for ($i = 0; $i < sizeof($facetData); $i = $i + 2) {
                                        if ($facetData[$i+1] != 0) {
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
                                        }
                                    }
                            $itemTabContent .= '</div>';
                        $itemTabContent .= '</div>';
                    }
                $itemTabContent .= '</form>';
            $itemTabContent .= '</div>';


            $storyTabContent .= '<div class="search-content-left">';
                $storyTabContent .= '<h2 class="theme-color">REFINE YOUR SEARCH</h2>';

                $storyTabContent .= '<form id="story-facet-form">';
                    foreach ($storyFacetFields as $storyFacetField) {
                        $storyTabContent .= '<div class="search-panel-default collapse-controller">';
                            $storyTabContent .= '<div class="search-panel-heading collapse-headline clickable" data-toggle="collapse" href="#story-'.$storyFacetField['fieldName'].'-area" 
                                                onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')
                                                        jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')">';
                                $storyTabContent .= '<h4 class="left-panel-dropdown-title">';  
                                    $storyTabContent .= '<li style="font-size:14px;">'.$storyFacetField['fieldLabel'].'</li>';
                                $storyTabContent .= '</h4>';
                                $storyTabContent .= '<i class="far fa-caret-circle-down collapse-icon theme-color" style="font-size: 17px; float:right; margin-top:17.4px;"></i>';
                            $storyTabContent .= '</div>';

                            $storyTabContent .= "<div id='story-".$storyFacetField['fieldName']."-area' class=\"facet-search-subsection collapse show\">";
                                    $facetData = $solrStoryData['facet_counts']['facet_fields'][$storyFacetField['fieldName']];
                                    for ($i = 0; $i < sizeof($facetData); $i = $i + 2) {
                                        if ($facetData[$i+1] != 0) {
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
                                        }
                                    }
                            $storyTabContent .= '</div>';
                        $storyTabContent .= '</div>';
                    }
                $storyTabContent .= '</form>';
            $storyTabContent .= '</div>';

            // Facets End


            // Results Start
            

            $itemTabContent .= '<div class="search-content-right">';
                $itemTabContent .= '<div class="search-content-right-header">';
                    $itemTabContent .= '<div class="search-content-results-headline search-headline">';
                        $itemTabContent .= $itemStart.' - '.$itemEnd.' of '.$itemCount.' results';
                    $itemTabContent .= '</div>';
                    
                    $itemTabContent .= '<div class="search-content-results-headline search-content-results-view search-division-detail">';
                        $itemTabContent .=    '<div class="result-viewtype">';
                            $itemTabContent .=    '<ul class="content-view-bar">';
                                $itemTabContent .=   '<li class="search-results-list-radio search-results-radio">';
                                $itemTabContent .=       '<input id="item-list-button" type="radio" name="view" form="item-facet-form" value="list" checked>';
                                $itemTabContent .=             '<label for="item-list-button" class="theme-color-background">';
                                $itemTabContent .=                  '<i class="far fa-th-list" style="font-size: 12px; padding-right: 3px;"></i>';
                                $itemTabContent .=                  'List';
                                $itemTabContent .=             '</label>';
                                $itemTabContent .=       '</input>';
                                $itemTabContent .=   '</li>';
                                $itemTabContent .=     '<li class="search-results-grid-radio search-results-radio">';
                                $itemTabContent .=         '<input id="item-grid-button" type="radio" name="view" form="item-facet-form" value="grid">';
                                $itemTabContent .=             '<label for="item-grid-button">';
                                $itemTabContent .=                  '<i class="far fa-th-large theme-color" style="font-size: 12px; padding-right: 3px;"></i>';
                                $itemTabContent .=                  'Grid';
                                $itemTabContent .=             '</label>';
                                $itemTabContent .=        '</input>';
                                $itemTabContent .=    '</li>';
                            $itemTabContent .=   '</ul>';
                        $itemTabContent .=   '</div>';
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
                        if (((($itemPage + $i) - 1) * 25) < $itemCount) {
                            $pagination .= '<button type="submit" form="item-facet-form" name="pi" value="'.($itemPage + $i).'" class="theme-color-hover" style="outline:none;">';
                                $pagination .= ($itemPage + $i);
                            $pagination .= '</button>';
                        }
                    }

                        // Right arrows
                    if ($itemPage < ceil($itemCount / 25)) {
                        $pagination .= '<button type="submit" form="item-facet-form" name="pi" value="'.ceil($itemCount / 25).'" class="theme-color-hover" style="outline:none;">';
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
                        $itemTabContent .= '<div class="search-page-single-result">';
                            $itemTabContent .= '<div class="search-page-single-result-info">';
                                $itemTabContent .= '<h2 class="theme-color">';
                                    $itemTabContent .= "<a href='".home_url( $wp->request )."/story/item?item=".$item['ItemId']."'>";
                                        $itemTabContent .= $item['Title'];
                                    $itemTabContent .= "</a>";
                                $itemTabContent .= '</h2>';
                                $itemTabContent .= '<p class="search-page-single-result-description">';
                                    $itemTabContent .= $item['Description'];
                                $itemTabContent .= '</p>';
                            $itemTabContent .= '</div>';
                            $itemTabContent .= '<div class="search-page-single-result-image">';
                            
                                $image = json_decode($story['PreviewImageLink'], true);
                                
                                $listImageLink = $image['service']['@id'];
                                $listImageLink .= "/full/300,/0/default.jpg";

                                $gridImageLink = $image['service']['@id'];
                                if ($image["width"] <= ($image["height"] * 2)) {
                                    $gridImageLink .= "/0,0,".$image["width"].",".($image["width"] / 2);
                                }
                                else {
                                    $gridImageLink .= "/".round(($image["width"] - $image["height"]) / 2).",0,".($image["height"] * 2).",".$image["height"];
                                }
                                $gridImageLink .= "/280,140/0/default.jpg";

                                $itemTabContent .= "<a class='list-view-image' href='".home_url( $wp->request )."/item?item=".$item['ItemId']."'>";
                                    $itemTabContent .= '<img src='.$listImageLink.'>';
                                $itemTabContent .= "</a>";
                                $itemTabContent .= "<a class='grid-view-image' style='display:none' href='".home_url( $wp->request )."/item?item=".$item['ItemId']."'>";
                                    $itemTabContent .= '<img src='.$gridImageLink.'>';
                                $itemTabContent .= "</a>";

                                $itemTabContent .= '<div class="search-document-progress-bar" style="height: 20px;
                                                background: #eeeeee;
                                                width: 100%;"></div>';
                            $itemTabContent .= '</div>';
                            
                            $itemTabContent .= '<div style="clear:both"></div>';
                        $itemTabContent .= '</div>';
                    }   
                $itemTabContent .= '</div>';

                // Pagination below search results
                $itemTabContent .= $pagination;
            $itemTabContent .= '</div>';


            $storyTabContent .= '<div class="search-content-right">';
                $storyTabContent .= '<div class="search-content-right-header">';
                    $storyTabContent .= '<div class="search-content-results-headline search-headline">';
                        $storyTabContent .= $storyStart.' - '.$storyEnd.' of '.$storyCount.' results';
                    $storyTabContent .= '</div>';
                    
                    $storyTabContent .= '<div class="search-content-results-headline search-content-results-view search-division-detail">';
                        $storyTabContent .=    '<div class="result-viewtype">';
                            $storyTabContent .=    '<ul class="content-view-bar">';
                                $storyTabContent .=   '<li class="search-results-list-radio search-results-radio">';
                                $storyTabContent .=       '<input id="story-list-button" type="radio" name="view" form="story-facet-form" value="list" checked>';
                                $storyTabContent .=             '<label for="story-list-button" class="theme-color-background">';
                                $storyTabContent .=                  '<i class="far fa-th-list" style="font-size: 12px; padding-right: 3px;"></i>';
                                $storyTabContent .=                  'List';
                                $storyTabContent .=             '</label>';
                                $storyTabContent .=       '</input>';
                                $storyTabContent .=   '</li>';
                                $storyTabContent .=     '<li class="search-results-grid-radio search-results-radio">';
                                $storyTabContent .=         '<input id="story-grid-button" type="radio" name="view" form="story-facet-form" value="grid">';
                                $storyTabContent .=             '<label for="story-grid-button">';
                                $storyTabContent .=                  '<i class="far fa-th-large theme-color" style="font-size: 12px; padding-right: 3px;"></i>';
                                $storyTabContent .=                  'Grid';
                                $storyTabContent .=             '</label>';
                                $storyTabContent .=        '</input>';
                                $storyTabContent .=    '</li>';
                            $storyTabContent .=   '</ul>';
                        $storyTabContent .=   '</div>';
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

                    // Previous page
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
                        if (((($storyPage + $i) - 1) * 25) < $storyPage) {
                            $pagination .= '<button type="submit" form="story-facet-form" name="ps" value="'.($storyPage + $i).'" class="theme-color-hover" style="outline:none;">';
                                $pagination .= ($storyPage + $i);
                            $pagination .= '</button>';
                        }
                    }

                        // Right arrows
                    if ($storyPage < ceil($storyCount / 25)) {
                        $pagination .= '<button type="submit" form="story-facet-form" name="ps" value="'.ceil($storyCount / 25).'" class="theme-color-hover" style="outline:none;">';
                            $pagination .= '&raquo;';
                        $pagination .= '</button>';
                    }
                    $pagination .= '<div style="clear:both;"></div>';
                $pagination .= '</div>';

                // Pagination on top of search results
                $storyTabContent .= $pagination;

                // Search results
                $storyTabContent .= '<div class="search-content-right-items">';
                    foreach ($solrStoryData['response']['docs'] as $story) {
                        $storyTabContent .= '<div class="search-page-single-result">';
                            $storyTabContent .= '<div class="search-page-single-result-info">';
                                $storyTabContent .= '<h2 class="theme-color">';
                                    $storyTabContent .= "<a href='".home_url( $wp->request )."/story?story=".$story['StoryId']."'>";
                                        $storyTabContent .= $story['dcTitle'];
                                    $storyTabContent .= "</a>";
                                $storyTabContent .= '</h2>';
                                $storyTabContent .= '<p class="search-page-single-result-description">';
                                    $storyTabContent .= $story['dcDescription'];
                                $storyTabContent .= '</p>';
                            $storyTabContent .= '</div>';
                            $storyTabContent .= '<div class="search-page-single-result-image">';
                            
                                $image = json_decode($story['PreviewImageLink'], true);
                                
                                $listImageLink = $image['service']['@id'];
                                $listImageLink .= "/full/300,/0/default.jpg";

                                $gridImageLink = $image['service']['@id'];
                                if ($image["width"] <= ($image["height"] * 2)) {
                                    $gridImageLink .= "/0,0,".$image["width"].",".($image["width"] / 2);
                                }
                                else {
                                    $gridImageLink .= "/".round(($image["width"] - $image["height"]) / 2).",0,".($image["height"] * 2).",".$image["height"];
                                }
                                $gridImageLink .= "/280,140/0/default.jpg";

                                $storyTabContent .= "<a class='list-view-image' href='".home_url( $wp->request )."/story?story=".$story['StoryId']."'>";
                                    $storyTabContent .= '<img src='.$listImageLink.'>';
                                $storyTabContent .= "</a>";
                                $storyTabContent .= "<a class='grid-view-image' style='display:none' href='".home_url( $wp->request )."/story?story=".$story['StoryId']."'>";
                                    $storyTabContent .= '<img src='.$gridImageLink.'>';
                                $storyTabContent .= "</a>";

                                $storyTabContent .= '<div class="search-document-progress-bar" style="height: 20px;
                                                background: #eeeeee;
                                                width: 100%;"></div>';
                            $storyTabContent .= '</div>';
                            
                            $storyTabContent .= '<div style="clear:both"></div>';
                        $storyTabContent .= '</div>';
                    }   
                $storyTabContent .= '</div>';

                // Pagination below search results
                $storyTabContent .= $pagination;
            $storyTabContent .= '</div>';
            
            // Results End


        $itemTabContent .= '</section>';
    $itemTabContent .= "</div>";

        $storyTabContent .= '</section>';
    $storyTabContent .= "</div>";

    if (is_string($_GET['qs']) || $_GET['ps'] != null) { 
        $content .= '<div id="search-page-item-tab" style="display: none;">';
            $content .= $itemTabContent;
        $content .= '</div>';
        $content .= '<div id="search-page-story-tab">';
            $content .= $storyTabContent;
        $content .= '</div>';  
    }
    else {
        $content .= '<div id="search-page-item-tab">';
            $content .= $itemTabContent;
        $content .= '</div>';
        $content .= '<div id="search-page-story-tab" style="display: none;">';
            $content .= $storyTabContent;
        $content .= '</div>';
    }
$content .= "</div>";

echo $content;


?>