<?php
global $wpdb;
$myid = uniqid(rand()).date('YmdHis');
$base = 0;
/* 
Description: Gets stories from the API and displays them
*/

//function _TCT_get_stories( $atts ) {  }

$theme_sets = get_theme_mods();
 /* Domain name of the Solr server */
 define('SOLR_SERVER_HOSTNAME', network_home_url());

 /* Whether or not to run in secure mode */
 define('SOLR_SECURE', true);

 /* HTTP Port to connection */
 define('SOLR_SERVER_PORT', ((SOLR_SECURE) ? 8443 : 8983));

 /* HTTP Basic Authentication Username */
 //define('SOLR_SERVER_USERNAME', 'admin');

 /* HTTP Basic Authentication password */
 //define('SOLR_SERVER_PASSWORD', 'changeit');

 /* HTTP connection timeout */
 /* This is maximum time in seconds allowed for the http data transfer operation. Default value is 30 seconds */
 define('SOLR_SERVER_TIMEOUT', 10);

 $url = network_home_url()."tp-api/storiesMinimal/count";
 $requestType = "GET";

 include get_stylesheet_directory() . '/admin/inc/custom_scripts/send_api_request.php';

 $storyCount = json_decode($result, true);

 $requestData = array(
     'key' => 'testKey'
 );
 $url = network_home_url()."tp-api/storiesMinimal";
 if ($_GET['pa'] != null && is_numeric($_GET['pa']) && (($_GET['pa'] - 1) * 25) < $storyCount && $_GET['pa'] != 0) {
    $url .= "?pa=".$_GET['pa'];
 }
 $requestType = "GET";

 include get_stylesheet_directory() . '/admin/inc/custom_scripts/send_api_request.php';

 $stories = json_decode($result, true);

 $content = "";

 $content .= "<style>
                 .search-bar input::placeholder {
                     color: ".$theme_sets['vantage_general_link_color'].";
                 }
             </style>";

$content .= '<section id="full-width-header" class="temp-back">';
$content .= '<div class="searchable">';
$content .= '<form class="search-bar" action="/action_page.php">';
$content .= '<div class="theme-color"><input type="text" placeholder="Add a search item" name="search"></div>';
$content .= '<button type="submit" class="theme-color-background"><i class="far fa-search" style="font-size: 20px; float:right;"></i></button>';
$content .= '</form>';
$content .= '</div>';

$content .= '</section>';

$content .= "<div id='primary-full-width'>";
$content .= '<section class="complete-search-content">';

$content .= '<div class="search-content-left">';
$content .= '<h2 class="theme-color">REFINE YOUR SEARCH</h2>';

$content .= '<div class="search-panel-default collapse-controller">';
    $content .= '<div class="search-panel-heading collapse-headline clickable" data-toggle="collapse" href="#type-area" 
        onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')
                 jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')">';
            $content .= '<h4 id="description-collapse-heading" class="left-panel-dropdown-title">';  
                $content .= '<li style="font-size:14px;">DOCUMENT TYPE</li>';
            $content .= '</h4>';
            $content .= '<i class="far fa-caret-circle-down collapse-icon theme-color" style="font-size: 17px; float:right; margin-top:17.4px;"></i>';
        $content .= '</div>';
        

        $content .= "<div id=\"type-area\" class=\"search-options-selection panel-body panel-collapse collapse\">";
        
        $content .= '<label class="search-container theme-color"> Diaries<input id="type-letter-checkbox" type="checkbox" checked="checked" name="doctype" value="card"><span  class=" theme-color-background checkmark"></span></label>';
        $content .= '<label class="search-container theme-color"> Letters<input type="checkbox" name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';
        $content .= '<label class="search-container theme-color"> Post cards<input type="checkbox"  name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';                        
        $content .= '<label class="search-container theme-color"> Pictures<input type="checkbox"  name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>'; 
        $content .= '</div>';
    $content .= '</div>';

    $content .= '<div class="search-panel-default collapse-controller">';
            $content .= '<div class="search-panel-heading collapse-headline clickable" data-toggle="collapse" href="#language-area" 
            onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')
                    jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')">';
                $content .= '<h4 id="description-collapse-heading" class="left-panel-dropdown-title">';  
                    $content .= '<li style="font-size:14px;">LANGUAGES</li>';
                $content .= '</h4>';
                $content .= '<i class="far fa-caret-circle-down collapse-icon theme-color" style="font-size: 17px; float:right; margin-top:17.4px;"></i>';
            $content .= '</div>';
            

            $content .= "<div id=\"language-area\" class=\"search-options-selection panel-body panel-collapse collapse\">";
            
            $content .= '<label class="search-container theme-color"> Deutsch<input id="type-letter-checkbox" type="checkbox" checked="checked" name="doctype" value="card"><span  class=" theme-color-background checkmark"></span></label>';
            $content .= '<label class="search-container theme-color"> English<input type="checkbox" name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';
            $content .= '<label class="search-container theme-color"> Norwegien<input type="checkbox"  name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';                        
            $content .= '<label class="search-container theme-color"> Unknown<input type="checkbox"  name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>'; 
            $content .= '</div>';
    $content .= '</div>';

    $content .= '<div class="search-panel-default collapse-controller">';
            $content .= '<div class="search-panel-heading collapse-headline clickable" data-toggle="collapse" href="#tags-area" 
            onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')
                    jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')">';
                $content .= '<h4 id="description-collapse-heading" class="left-panel-dropdown-title">';  
                    $content .= '<li style="font-size:14px;">SHORT TAGS</li>';
                $content .= '</h4>';
                $content .= '<i class="far fa-caret-circle-down collapse-icon theme-color" style="font-size: 17px; float:right; margin-top:17.4px;"></i>';
            $content .= '</div>';
            

            $content .= "<div id=\"tags-area\" class=\"search-options-selection panel-body panel-collapse collapse\">";
            
            $content .= '<label class="search-container theme-color"> Children<input id="type-letter-checkbox" type="checkbox" checked="checked" name="doctype" value="card"><span  class=" theme-color-background checkmark"></span></label>';
            $content .= '<label class="search-container theme-color"> Art<input type="checkbox" name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';
            $content .= '<label class="search-container theme-color"> Architecture<input type="checkbox"  name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';                        
            $content .= '<label class="search-container theme-color"> Historic<input type="checkbox"  name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>'; 
            $content .= '</div>';
    $content .= '</div>';

    $content .= '<div class="search-panel-default collapse-controller">';
            $content .= '<div class="search-panel-heading collapse-headline clickable" data-toggle="collapse" href="#status-area" 
            onClick="jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-down\')
                    jQuery(this).find(\'.collapse-icon\').toggleClass(\'fa-caret-circle-up\')">';
                $content .= '<h4 id="description-collapse-heading" class="left-panel-dropdown-title">';  
                    $content .= '<li style="font-size:14px;">DOCUMENT STATUS</li>';
                $content .= '</h4>';
                $content .= '<i class="far fa-caret-circle-down collapse-icon theme-color" style="font-size: 17px; float:right; margin-top:17.4px;"></i>';
            $content .= '</div>';

            $content .= "<div id=\"status-area\" class=\"search-options-selection panel-body panel-collapse collapse\">";
            
            $content .= '<label class="search-container theme-color"> Not started<input id="type-letter-checkbox" type="checkbox" checked="checked" name="doctype" value="card"><span  class=" theme-color-background checkmark"></span></label>';
            $content .= '<label class="search-container theme-color"> Started<input type="checkbox" name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';
            $content .= '<label class="search-container theme-color"> In review<input type="checkbox"  name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>';                        
            $content .= '<label class="search-container theme-color"> Completed<input type="checkbox"  name="doctype" value="card"><span class="theme-color-background checkmark"></span></label>'; 
            $content .= '</div>';
    $content .= '</div>';
$content .= '</div>';
    $content .= '<div class="search-content-right">';
        $content .= '<div class="search-content-right-header">';
            $content .= '<div class="search-content-results-headline search-headline">';
                $page = $_GET['pa'];
                if ($page != null && is_numeric($page) && (($page - 1) * 25) < $storyCount && $page != 0){
                    $storyStart = (($page - 1) * 25) + 1;
                    $storyEnd = $page * 25;
                }
                else {
                    $page = 1;
                    $storyStart = 1;
                    $storyEnd = 25;
                }
                $content .= $storyStart.' - '.$storyEnd.' of '.$storyCount.' results';
            $content .= '</div>';
            
            $content .= '<div class="search-content-results-headline search-content-results-view search-division-detail">';
                        
                    $content .=    '<div class="result-viewtype" id="btnContainer">';
                        $content .=    '<ul class="content-view-bar">';
                            $content .=   '<li class="content-view-list">';
                            $content .=       '<button class="content-view-button view-btn" id="list">';
                            $content .= '<i class="far fa-th-list theme-color" style="font-size: 12px; padding-right: 3px;"></i>';
                            $content .=           'List';
                            $content .=       '</button>';
                            $content .=   '</li>';
                            $content .= '<li class="content-view-grid" id="grid">';
                            $content .= '<button class="content-view-button view-btn">';
                            $content .= '<i class="far fa-th-large theme-color" style="font-size: 12px; padding-right: 3px;"></i>';
                            $content .=            'Grid';
                            $content .=        '</button>';
                            $content .=    '</li>';
                        $content .=   '</ul>';
                    $content .=   '</div>';
            $content .= '</div>';
        
             /*    $content .= '<div class="search-content-results-headline search-division-detail">';
                        $content .= '<div class="">';
                            $content .= '<span class="">Per page</span>';
                                $content .= '<div class="">';
                                    $content .= '<a class="" href="#" data-dropdown="">';
                                    $content .= '12';
                                    $content .= '</a>';
                                    $content .= '<div id="" class="">';
                                    $content .= '<ul class="">';
                                        $content .= '<li class="active"><a href="/portal/en/search?locale=en&amp;per_page=12&amp;q=" >12</a></li>';
                                        $content .= '<li><a href="/portal/en/search?locale=en&amp;per_page=24&amp;q=" >24</a></li>';
                                        $content .= '<li><a href="/portal/en/search?locale=en&amp;per_page=36&amp;q=" >36</a></li>';
                                        $content .= '<li><a href="/portal/en/search?locale=en&amp;per_page=48&amp;q=" >48</a></li>';
                                        $content .= '<li ><a href="/portal/en/search?locale=en&amp;per_page=72&amp;q=" >72</a></li>';
                                        $content .= '<li ><a href="/portal/en/search?locale=en&amp;per_page=96&amp;q=" >96</a></li>';
                                    $content .= '</ul>';
                                $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';*/
         $content .= '</div>';
         
        // Search result pagination
        $pagination = "";
        $pagination .= '<div class="story-search-pagination">';
            // Left arrows
            if ($page > 1) {
                $pagination .= '<a class="theme-color-hover" style="outline:none;" href='.home_url( $wp->request ).'?pa=1>';
                    $pagination .= '&laquo;';
                $pagination .= '</a>';
            }

            // Previous page
             if ($page != null && is_numeric($page) && $page > 1) {
                 $pagination .= '<a class="theme-color-hover" style="outline:none;" href='.home_url( $wp->request ).'?pa='.($page - 1).'>';
                     $pagination .= ($page - 1);
                 $pagination .= '</a>';
             }

            // Current page
             $pagination .= '<a class="theme-color-background" style="outline: none; pointer-events: none; cursor: default;">';
                 $pagination .= $page;
             $pagination .= '</a>';

            // 3 next pages
            for ($i = 1; $i <= 3; $i++) {
                 if (((($page + $i) - 1) * 25) < $storyCount) {
                     $pagination .= '<a class="theme-color-hover" style="outline:none;" href='.home_url( $wp->request ).'?pa='.($page + $i).'>';
                         $pagination .= ($page + $i);
                     $pagination .= '</a>';
                 }
            }

             // Right arrows
            if ($page < ceil($storyCount / 25)) {
                $pagination .= '<a class="theme-color-hover" style="outline:none;" href='.home_url( $wp->request ).'?pa='.ceil($storyCount / 25).'>';
                    $pagination .= '&raquo;';
                $pagination .= '</a>';
            }
            $pagination .= '<div style="clear:both;"></div>';
         $pagination .= '</div>';

        // Pagination on top of search results
         $content .= $pagination;

        // Search results
         $content .= '<div class="search-content-right-items">';
            foreach ($stories as $story){
                $content .= '<div class="story-search-single-result">';
                    $content .= '<div class="story-search-single-result-info">';
                        $content .= '<h2 class="theme-color">';
                            $content .= "<a href='".home_url( $wp->request )."/story?story=".$story['StoryId']."'>";
                                $content .= $story['dcTitle'];
                            $content .= "</a>";
                        $content .= '</h2>';
                        $content .= '<p class="story-search-single-result-description">';
                            $content .= $story['dcDescription'];
                        $content .= '</p>';
                    $content .= '</div>';
                    $content .= '<div class="story-search-single-result-image">';
                    
                        
                        $image = json_decode($story['PreviewImageLink'], true);
                        $imageLink = $image['service']['@id'];
                        if ($image["width"] <= $image["height"]) {
                            $imageLink .= "/0,0,".$image["width"].",".$image["width"];
                        }
                        else {
                            $imageLink .= "/0,0,".$image["height"].",".$image["height"];
                        }
                        $imageLink .= "/280,140/0/default.jpg";
                        /*
                        $image = json_decode($story['PreviewImageLink'], true);
                        $imageLink = $image['service']['@id'];
                        $imageLink .= "/full/300,/0/default.jpg";*/

                        $content .= "<a href='".home_url( $wp->request )."/story?story=".$story['StoryId']."'>";
                            $content .= '<img src='.$imageLink.'>';
                        $content .= "</a>";
                    $content .= '</div>';
                    $content .= '<div style="clear:both"></div>';
                $content .= '</div>';

            }   
            
        $content .= '<script>
                        jQuery(document).ready(function(){
                            jQuery("#grid").click(function(){
                            jQuery(".story-search-single-result").addClass("maingridview");
                            jQuery(".story-search-single-result-info").removeClass(".story-search-single-result-description");
                            jQuery(this).addClass("active").prev().removeClass("active");
                            });
                            jQuery("#list").click(function(){
                            jQuery(".story-search-single-result").removeClass("maingridview");
                            jQuery(".story-search-single-result-info").addClass(".story-search-single-result-description");

                                jQuery(this).addClass("active").next().removeClass("active");
                            });
                        });
        </script>';
        $content .= '</div>';

        
        // Pagination below search results
        $content .= $pagination;

    
 $content .= '</section>';
$content .= "</div>";




/*    $content .= '<div class="search-page">';
     $content .= '<div class="search-page-left" style="background-color:#ddd;">';
         $content .= '<h2>refine searches</h2>';
         $content .= '<ul id="search-menu">';*/
////



////
/*              $content .= '<li><a href="#">STATUS</a></li>';
             $content .= '<li><a href="#">COUNTRY</a></li>';
             $content .= '<li><a href="#">LANGUAGES</a></li>';
             $content .= '<li><a href="#">KEY WORDS</a></li>';
             $content .= '<li><a href="#">MEDIA</a></li>';
         $content .= '</ul>';
     $content .= '</div>';  

     $content .= '<div class="search-page-right" style="background-color:#fff;">';
         $content .= '<h2>Page Content</h2>';
         $content .= '<p>Start to type for a spe "filter" the search options.</p>';
         $content .= '<p>Some text..</p>';
     $content .= '</div>';
 $content .= '</div>';*/

 echo $content;


?>