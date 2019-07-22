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

 //global $wp;
 // Set Post content
 $requestData = array(
     'key' => 'testKey'
 );
 $url = network_home_url()."tp-api/storiesMinimal";
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


    $content .= '<div class="search-content-right">';
        $content .= '<div class="search-content-right-header">';
                $content .= '<div class="search-content-results-headline search-headline">';
                $content .= '00 - 25 of 99999999 results';
                $content .= '</div>';
               
                $content .= '<div class="search-content-results-headline search-content-results-view search-division-detail">';
                            
                        $content .=    '<div class="result-viewtype">';
                            $content .=    '<ul class="content-view-bar">';
                                $content .=    '<li class="content-view-grid">';
                                $content .=        '<a href="" class="content-view-button">';
                                $content .= '<i class="far fa-th-large theme-color" style="font-size: 12px; padding-right: 3px;"></i>';
                                $content .=            'Grid';
                                $content .=        '</a>';
                                $content .=    '</li>';
                                $content .=   '<li class="content-view-list">';
                                $content .=       '<a href="" class="content-view-button">';
                                $content .= '<i class="far fa-th-list theme-color" style="font-size: 12px; padding-right: 3px;"></i>';
                                $content .=           'List';
                                $content .=       '</a>';
                                $content .=   '</li>';
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
         $content .= '<div class="search-content-right-items">';
         
         foreach ($stories as $story){
             $content .= "<a href='".home_url( $wp->request )."/story?story=".$story['StoryId']."'>".$story['dcTitle']."</a></br>";
         }
         
     
         $content .= "<br><div style='font-size:25px;'><a href='".home_url()."/item_page_test_iiif/?item=3549'>IIIF example</a></div>";
     $content .= '</div>';
     $content .= '</div>';


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
     $content .= '<div style="clear:both;"></div>';
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