<?php
// get Document data from API
function _TCT_get_document_data( $atts ) {   
    $content = "";
    if (isset($_GET['story']) && $_GET['story'] != "") {
        // get Story Id from url parameter
        $storyId = $_GET['story'];

        // Set request parameters
        $url = network_home_url()."/tp-api/stories/".$storyId;
        $requestType = "GET";
    
        // Execude request
        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

        // Display data
        $storyData = json_decode($result, true);
        $storyData = $storyData[0];

        // Top image slider 
        $content .= "<div class='story-page-slider'>";
            foreach ($storyData['Items'] as $item) {
                $image = json_decode($item['ImageLink'], true);
                $imageLink = $image['service']['@id'];
                if ($image["width"] <= $image["height"]) {
                    $imageLink .= "/0,0,".$image["width"].",".$image["width"];
                }
                else {
                    $imageLink .= "/0,0,".$image["height"].",".$image["height"];
                }
                $imageLink .= "/250,250/0/default.jpg";
                $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."'><img data-lazy='".$imageLink."'></a>";
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
                        <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258371.full-150x150.jpg'></div>";*/
        $content .= "</div>";

        // Image slider JavaScript
        $content .= "<script>
            jQuery(document).ready(function(){
                jQuery('.story-page-slider').slick({
                    dots: true,
                    arrows: false,
                    speed: 300,
                    slidesToShow: 7,
                    slidesToScroll: 7,
                    lazyLoad: 'ondemand',
                    responsive: [
                        {
                            breakpoint: 1650,
                            settings: {
                            slidesToShow: 6,
                            slidesToScroll: 6,
                            infinite: true,
                            dots: true
                            }
                        },
                        {
                            breakpoint: 1400,
                            settings: {
                            slidesToShow: 5,
                            slidesToScroll: 5,
                            infinite: true,
                            dots: true
                            }
                        },
                        {
                            breakpoint: 1150,
                            settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4,
                            infinite: true,
                            dots: true
                            }
                        },
                        {
                            breakpoint: 900,
                            settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                            }
                        },
                        {
                            breakpoint: 650,
                            settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                            }
                        },
                        {
                            breakpoint: 400,
                            settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                            }
                        }
                    ]
                })
            });
        </script>";

$content .= "<div id='total-storypg' class='storypg-container'>";
    $content .= "<div class='main-storypg'>";
                $content .= "<div class='storypg-info'>";
                    $content .= "<h1 class='storypg-title'>Scrisoare către Elena Mureșianu de la fiul ei Aurel A. Mureșianu</h1>";

                        $content .= "<p><strong>Title in English</strong><br />";
                        $content .= "Letter for Elena Mureșianu from his son.";
                        $content .= "</p>";
                        $content .= "<strong>Description</strong>";
                    $content .= "<div>";
                            $content .= "Aurel A. Mureșianu (1889-1950), aflat pe frontul Primului Război Mondial, către mama sa, Elena (1862-1924). Îi descrie condițiile de cazare. Trag cu tunurile și ziua și noaptea. Otilia Mureșianu (soția lui Iacob Mureșianu, compozitorul, 1857-1917) i-a trimis o scrisoare care l-a înduioșat.";
                    $content .= "</div>";
                        $content .= "<p>";
                        $content .= "<strong>Summary description of items</strong><br />";
                    $content .= "<div>";
                        $content .= "Scrisoare olografă de 3 pagini și un plic.";
                    $content .= "</div>";
                        $content .= "</p>";
                $content .= "</div>";

            //Status Chart
            $content .= "<div id='story-page-right-side' class='storypg-chart'>";

                // Set request parameters for status data
                $url = network_home_url()."/tp-api/completionStatus";
                $requestType = "GET";
            
                // Execude http request
                include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                // Save status data
                $statusTypes = json_decode($result, true);

                $statusCount = array(
                                    "Not Started" => 0,
                                    "Edit" => 0,
                                    "Review" => 0,
                                    "Completed" => 0
                                );
                $itemCount = 0;
                foreach ($storyData['Items'] as $item) {
                    //var_dump($item['CompletionStatusName']);
                    switch ($item['CompletionStatusName']){
                        case 'Not Started':
                            $statusCount['Not Started'] += 1; 
                            break;
                        case 'Edit':
                            $statusCount['Edit'] += 1;
                            break;
                        case 'Review':
                            $statusCount['Review'] += 1;
                            break;
                        case 'Completed':
                            $statusCount['Completed'] += 1;
                            break;
                    }
                    
                    $itemCount += 1;
                }
                echo $statusCount['Not-Started'];
                $content .= "<strong>Transcription status</strong><br />";
                $content .= "<canvas id='statusChart' class='status-main-chart' width='200' height='200'></canvas>";
                $content .= "<script>
                                var ctx = document.getElementById('statusChart');
                                var myDoughnutChart = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels :['Not Started','Edit','Review','Completed'],
                                        datasets: [{
                                            data: [".$statusCount['Not Started'].", ".$statusCount['Edit'].", ".$statusCount['Review'].", ".$statusCount['Completed']."],
                                            backgroundColor: [";
                                                foreach ($statusTypes as $statusType) {
                                                    $content .= '"'.$statusType['ColorCode'].'", ';
                                                }
                $content .=                 "]
                                        }]
                                    },
                                    options: {
                                        
                                        tooltips: {
                                          titleFontSize: 10,
                                          bodyFontSize: 10
                                        },
                                        legend : {
                                                    display: false
                                                },
                                      
                                        responsive: false
                                    }
                                });
                            </script>";
                            
                            $statusPercentage = array(
                                "Not Started" => intval(($statusCount['Not Started']*100)/$itemCount),
                                "Edit" => intval(($statusCount['Edit']*100)/$itemCount),
                                "Review" => intval(($statusCount['Review']*100)/$itemCount),
                                "Completed" => intval(($statusCount['Completed']*100)/$itemCount)
                            );

                $content .= "<table width=\"100%\">\n";
                                    foreach ($statusTypes as $statusType) {
                                        $content .= "<tr>\n";
                                            $content .= "<td><span class=\"colorbox\" style='background-color: ".$statusType['ColorCode']."'></span></td>\n";
                                            $content .= "<td>".$statusType['Name']."</td>\n";
                                            $content .= "<td class=\"alg_r\">".$statusPercentage[$statusType['Name']]." %</td>\n";
                                        $content .= "</tr>\n";
                                    }
                                $content .= "</table>\n";
    
                            $content .= "<div class=\"facts\">\n";
						
                                        $content .= "<dl>\n";
                                        $content .= "<dt>"._x('ID','Documents-Single Document','transcribathon')."</dt>\n";
                                        $content .= "<dd>".$custom['tct_story_id'][0]."</dd>\n";
                                        $content .= "</dl>\n";
                                        $content .= "<dl>\n";
                                        $content .= "<dt>"._x('Source','Documents-Single Document','transcribathon')."</dt>\n";
                                        $content .= "<dd><a title=\"http://embed.europeana1914-1918.eu/".$custom['tct_story_id'][0]."\" href=\"http://embed.europeana1914-1918.eu/".$custom['tct_story_id'][0]."\" target=\"_blank\">http://europeana1914-1918.eu/...</a></dd>\n";
                                        $content .= "</dl>\n";
                                        $items = get_posts( array('post_type' => 'documents','post_parent' => $post->ID,'numberposts' => -1));
                                        $content .= "<dl>\n";
                                        $content .= "<dt>"._x('Number of items','Documents-Single Document','transcribathon')."</dt>\n";
                                        $content .= "<dd>".sizeof($items)."</dd>\n";
                                        $content .= "</dl>\n";
                                        
                                        
                                  //      $count = 0;							
                                  //      if($custom['tct_character1_given_name'][0] != "" || $custom['tct_character1_family_name'][0] != ""){
                                  //          if($custom['tct_character2_given_name'][0] != "" || $custom['tct_character2_family_name'][0] != ""){ $count=1; }
                                  //      }
                                        // person 1
                                  //      if($custom['tct_character1_given_name'][0] != "" || $custom['tct_character1_family_name'][0] != ""){
                                            $content .= "<dl>\n";
                                  //          if($count > 0){ $cnt=' 1'; }else{ $cnt= ''; }
                                            $content .= "<dt>"._x('Person','Documents-Single Document','transcribathon').$cnt."</dt>\n";
                                            $name = $custom['tct_character1_given_name'][0];
                                  //          if($custom['tct_character1_family_name'][0] != ""){ if($name != ""){ $name .= " "; } $name .= $custom['tct_character1_family_name'][0];}
                                            $content .= "<dd>".$name;
                                  //          if($custom['tct_character1_dob'][0] != "" && $custom['tct_character1_dob'][0] != "0000-00-00"){
                                  //              $content .= "<br />"._x('Born','Documents-Single Document: born ... in ...','transcribathon').": ".translateDate($custom['tct_character1_dob'][0]);
                                    //            if($custom['tct_character1_pob'][0] != ""){ $content .= " "._x('in','Documents-Single Document: born ... in ...','transcribathon')." ".$custom['tct_character1_pob'][0]; }
                                   //         }
                                   //         if($custom['tct_character1_dod'][0] != "" && $custom['tct_character1_dod'][0] != "0000-00-00"){
                                 //               $content .= "<br />"._x('Died','Documents-Single Document: born ... in ...','transcribathon').": ".translateDate($custom['tct_character1_dod'][0]);
                                   //             if($custom['tct_character1_pod'][0] != ""){ $content .= " "._x('in','Documents-Single Document: born ... in ...','transcribathon')." ".$custom['tct_character1_pod'][0]; }
                                   //         }
                                            $content .= "</dd>\n";
                                            $content .= "</dl>\n";
                                   //     }
                                        // person 2
                                    //    if($custom['tct_character2_given_name'][0] != "" || $custom['tct_character2_family_name'][0] != ""){
                                            $content .= "<dl>\n";
                                    //        if($count > 0){ $cnt=' 2'; }else{ $cnt= ''; }
                                            $content .= "<dt>"._x('Person','Documents-Single Document','transcribathon').$cnt."</dt>\n";
                                            $name = $custom['tct_character2_given_name'][0];
                                    //        if($custom['tct_character2_family_name'][0] != ""){ if($name != ""){ $name .= " "; } $name .= $custom['tct_character2_family_name'][0];}
                                            $content .= "<dd>".$name;
                                    //        if($custom['tct_character2_dob'][0] != "" && $custom['tct_character2_dob'][0] != "0000-00-00"){
                                    //            $content .= "<br />"._x('Born','Documents-Single Document: born ... in ...','transcribathon').": ".translateDate($custom['tct_character2_dob'][0]);
                                    //            if($custom['tct_character2_pob'][0] != ""){ $content .= " "._x('in','Documents-Single Document: born ... in ...','transcribathon')." ".$custom['tct_character2_pob'][0]; }
                                    //        }
                                    //        if($custom['tct_character2_dod'][0] != "" && $custom['tct_character2_dod'][0] != "0000-00-00"){
                                    //            $content .= "<br />"._x('Died','Documents-Single Document: born ... in ...','transcribathon').": ".translateDate($custom['tct_character2_dod'][0]);
                                    //            if($custom['tct_character2_pod'][0] != ""){ $content .= " "._x('in','Documents-Single Document: born ... in ...','transcribathon')." ".$custom['tct_character2_pod'][0]; }
                                     //       }
                                            $content .= "</dd>\n";
                                            $content .= "</dl>\n";
                                    //    }
                                    
                                        // Date of story
                                    //    $smallest_itemdate = $wpdb->get_results("SELECT pm1.meta_value FROM ".$wpdb->prefix."posts pst LEFT JOIN ".$wpdb->prefix."postmeta pm1 ON pm1.post_id=pst.ID WHERE pst.post_parent = '".$post->ID."' AND pm1.meta_key = 'tct_user_date_from' AND pm1.meta_value < (SELECT pmx.meta_value FROM ".$wpdb->prefix."postmeta pmx WHERE pmx.post_id='".$post->ID."' AND pmx.meta_key='tct_date_from') ORDER BY pm1.meta_value ASC LIMIT 0,1",ARRAY_N);
                                        //$highest_itemdate = $wpdb->get_results("SELECT pm1.meta_value FROM ".$wpdb->prefix."posts pst LEFT JOIN ".$wpdb->prefix."postmeta pm1 ON pm1.post_id=pst.ID WHERE pst.post_parent = '".$post->ID."' AND pm1.meta_key = 'tct_user_date_to' AND pm1.meta_value > (SELECT pmx.meta_value FROM ".$wpdb->prefix."postmeta pmx WHERE pmx.post_id='".$post->ID."' AND pmx.meta_key='tct_date_to') ORDER BY pm1.meta_value DESC LIMIT 0,1",ARRAY_N);
                                    //    $highest_itemdate = $wpdb->get_results("SELECT (CASE WHEN pm2.meta_value IS NULL AND pm1.meta_value IS NOT NULL THEN pm1.meta_value WHEN pm2.meta_value IS NOT NULL AND pm1.meta_value IS NOT NULL AND pm1.meta_value > pm2.meta_value THEN pm1.meta_value  WHEN pm2.meta_value IS NOT NULL AND pm1.meta_value IS NOT NULL AND pm2.meta_value > pm1.meta_value THEN pm2.meta_value  ELSE NULL END) AS highest FROM ".$wpdb->prefix."posts pst LEFT JOIN ".$wpdb->prefix."postmeta pm2 ON pm2.post_id=pst.ID LEFT JOIN ".$wpdb->prefix."postmeta pm1 ON pm1.post_id=pst.ID WHERE pst.post_parent = '".$post->ID."' AND pm2.meta_key = 'tct_user_date_to' AND pm1.meta_key='tct_user_date_from' AND (pm2.meta_value > (SELECT pmx.meta_value FROM ".$wpdb->prefix."postmeta pmx WHERE pmx.post_id='".$post->ID."' AND pmx.meta_key='tct_date_to') OR pm1.meta_value > (SELECT pmx.meta_value FROM ".$wpdb->prefix."postmeta pmx WHERE pmx.post_id='".$post->ID."' AND pmx.meta_key='tct_date_to')) ORDER BY highest DESC LIMIT 0,1",ARRAY_N);
                                        
                                        
                                        
                                    //    if($custom['tct_date_from'][0] != "" || $custom['tct_date_to'][0] != "" || !empty($smallest_itemdate) || !empty($highest_itemdate)){
                                            $content .= "<dl>\n";
                                            $content .= "<dt>"._x('Origin date','Documents-Single Document','transcribathon')."</dt>\n";
                                     //       $dt = "";
                                     //       if($custom['tct_date_from'][0] != ""){
                                     //           if(!empty($smallest_itemdate)){
                                     //               $dt .= translateDate($smallest_itemdate[0][0]);
                                     //           }else{
                                     //               $dt .= translateDate($custom['tct_date_from'][0]);
                                      //          }
                                      //      }else{
                                      //          if(!empty($smallest_itemdate)){
                                      //              $dt .= translateDate($smallest_itemdate[0][0]);	
                                      //          }
                                      //      }
                                      //      if($custom['tct_date_to'][0] != ""){
                                      //          if($dt != ""){ $dt .= " &ndash; "; }
                                      //          if(!empty($highest_itemdate)){
                                     //               $dt .= translateDate($highest_itemdate[0][0]);
                                      //          }else{
                                      //              $dt .= translateDate($custom['tct_date_to'][0]);
                                   //             }
                                   //         }
                                            $content .= "<dd>".$dt."</dd>\n";
                                            $content .= "</dl>\n";
                                   //     }
                                    
                                        
                                   // }else{
                                        
                                                                
                                        $content .= "<dl>\n";
                                        $content .= "<dt>"._x('ID','Documents-Single Document','transcribathon')."</dt>\n";
                                        $content .= "<dd>".$custom['tct_story_id'][0]." / ".$custom['tct_item_id'][0]."</dd>\n";
                                        $content .= "</dl>\n";	
                                        
                                        $content .= "<dl>\n";
                                        $content .= "<dt>"._x('Source','Documents-Single Document','transcribathon')."</dt>\n";
                                //        $content .= "<dd><a title=\"http://embed.europeana1914-1918.eu/".ICL_LANGUAGE_CODE."/contributions/".$custom['tct_story_id'][0]."#prettyPhoto[gallery]/"._TCT_getItemPosition($post->post_parent,$post->ID)."/\" href=\"http://embed.europeana1914-1918.eu/".ICL_LANGUAGE_CODE."/contributions/".$custom['tct_story_id'][0]."#prettyPhoto[gallery]/"._TCT_getItemPosition($post->post_parent,$post->ID)."/\" target=\"_blank\">http://europeana1914-1918.eu/...</a></dd>\n";
                                        $content .= "</dl>\n";
                                        
                                   //     $count = 0;	
                                   
                                        
                                   // }
                                    
                                    
                                    //if(is_story()){
                                        
                                            // languages 
                                       //     $terms = get_the_terms($post->ID, 'tct_languages' );
                                       //     if($terms && !is_wp_error($terms)){
                                       //         $langs = array();
                                        //        foreach($terms as $term){
                                        //            $langs[] = $term->name;
                                        //        }
                                        //    }
                                        //    if(isset($langs) && is_array($langs) && sizeof($langs)>0){
                                                $content .= "<dl>\n";
                                        //        $content .= "<dt>"._n('Language','Languages',(int)sizeof($langs),'transcribathon')."</dt>\n";
                                        //        $content .= "<dd>".implode(', ',$langs)."</dd>\n";
                                                $content .= "</dl>\n";
                                        //    }
                                            // Keywords
                                        //    $terms = get_the_terms($post->ID, 'keywords' );
                                        //    if($terms && !is_wp_error($terms)){
                                        //        $keys = array();
                                        //        foreach($terms as $term){
                                        //            $keys[] = $term->name;
                                        //        }
                                        //    }
                                        //    if(isset($keys) && is_array($keys) && sizeof($keys)>0){
                                                $content .= "<dl>\n";
                                        //        $content .= "<dt>"._n('Keyword','Keywords',(int)sizeof($keys),'transcribathon')."</dt>\n";
                                        //        $content .= "<dd>".implode(', ',$keys)."</dd>\n";
                                                $content .= "</dl>\n";
                                        //    }
                                            // Theatres
                                        //    $terms = get_the_terms($post->ID, 'theatres' );
                                        //    if($terms && !is_wp_error($terms)){
                                            //    $theatres = array();
                                            //    foreach($terms as $term){
                                            //        $theatres[] = $term->name;
                                          //      }
                                        //    }
                                      //      if(isset($theatres) && is_array($theatres) && sizeof($theatres)>0){
                                                $content .= "<dl>\n";
                                     //           $content .= "<dt>"._n('Front','Fronts',(int)sizeof($theatres),'transcribathon')."</dt>\n";
                                     //           $content .= "<dd>".implode(', ',$theatres)."</dd>\n";
                                                $content .= "</dl>\n";
                                    //        }
                                            // Forces 
                                    //        $terms = get_the_terms($post->ID, 'tct_forces' );
                                    //        if($terms && !is_wp_error($terms)){
                                    //            $forces = array();
                                    //            foreach($terms as $term){
                                    //                $forces[] = $term->name;
                                    //            }
                                    //        }
                                    //        if(isset($forces) && is_array($forces) && sizeof($forces)>0){
                                                $content .= "<dl>\n";
                                    //            $content .= "<dt>"._n('Force','Forces',(int)sizeof($forces),'transcribathon')."</dt>\n";
                                    //            $content .= "<dd>".implode(', ',$forces)."</dd>\n";
                                                $content .= "</dl>\n";
                                    //        }
                                    
                                    
                                    //}
                                    
                                    // Location-title
                                    //if(is_story()){
                                    //    if($custom['tct_location_placename'][0] != ""){
                                            $content .= "<dl>\n";
                                            $content .= "<dt>"._x('Location','Documents-Single Document','transcribathon')."</dt>\n";
                                            $content .= "<dd>".$custom['tct_location_placename'][0]."</dd>\n";
                                            $content .= "</dl>\n";
                                    //    }
                                    //}
                                    
                                    
                                    
                                        $content .= "<dl>\n";
                                        $content .= "<dt>"._x('Contributor','Documents-Single Document','transcribathon')."</dt>\n";
                                        $content .= "<dd>".$custom['tct_contributor_behalf'][0]."</dd>\n";
                                        $content .= "</dl>\n";
                                   
                                    // Licence
                                    //if($custom['tct_license'][0] != ""){
                                        $content .= "<dl>\n";
                                        $content .= "<dt>"._x('License','Documents-Single Document','transcribathon')."</dt>\n";
                                        $content .= "<dd><a href=\"".$custom['tct_license'][0]."\" target=\"_blank\">".$custom['tct_license'][0]."</a></dd>\n";
                                        $content .= "</dl>\n";
                                    //}
                                    
                                    // Collection day
                                    //if($custom['tct_collecction_day'][0] != "" && $custom['tct_collecction_day'][0] != "UNKNOWN" && $custom['tct_collecction_day'][0] != "INTERNET"){
                                        $content .= "<dl>\n";
                                        $content .= "<dt>"._x('Collection day','Documents-Single Document','transcribathon')."</dt>\n";
                                        $content .= "<dd><a href=\"https://transcribathon.com/en/documents/?cd=".$custom['tct_collecction_day'][0]."\">".$custom['tct_collecction_day'][0]."</a></dd>\n";
                                        $content .= "</dl>\n";
                                    //}
                       
                            $content .= "</div>\n";

            $content .= "</div>";
            $content .= "<div class='size'></div>";
    $content .= "</div>";
$content .= "</div>";








        /* POPUP modal code for items. Not in use for now
        // build page content
        foreach ($data['Items'] as $item){
            // create button to open item
            $content .= "<button id=".$item['ItemId']." type='button' class='btn btn-success openBtn'>".$item['Title']."</button>";
        }

            // create modal window structure
            $content .= '
                <!-- Modal -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog item-modal">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-body">';

                            // Item content will be loaded into this section

            $content .=     '</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>';
            
            // get Item content from getItemContent.php
            $content .= '
                <script>
                    jQuery(".openBtn").on("click",function(){
                        jQuery(".modal-body").load("/../wp-content/themes/transcribathon/getItemContent.php?id=" + jQuery(this).attr("id") ,function(){
                            jQuery("#myModal").modal({show:true});
                        });
                    });
                </script>';

        // opem modal window on page load if direct item link is used
        if (isset($_GET['item']) && $_GET['item'] != "") {
            $content .= '
            <script>
                jQuery(document).ready(function(){
                    jQuery(".modal-body").load("/../wp-content/themes/transcribathon/getItemContent.php?id=" + '.$_GET['item'].' ,function(){
                        jQuery("#myModal").modal({show:true});
                    });
                });
            </script>';
        }
        */
    }
    return $content;
}
add_shortcode( 'get_document_data', '_TCT_get_document_data' );
?>