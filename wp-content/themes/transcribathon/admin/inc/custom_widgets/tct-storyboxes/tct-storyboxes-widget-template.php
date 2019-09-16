<?php
global $wpdb;
$myid = uniqid(rand()).date('YmdHis');
$base = 0;

if ( ! is_admin() ) {

   
                if($instance['tct-storyboxes-headline'] != ""){ echo "<h1>".str_replace("\n","<br />",$instance['tct-storyboxes-headline'])."</h1>\n"; }
                $stories = array();
                $docs = array();
                if(isset($instance['tct-storyboxes-storybunch']) && trim($instance['tct-storyboxes-storybunch']) != ""){ 
                    $url = home_url()."/tp-api/storiesMinimal?storyId=".trim($instance['tct-storyboxes-storybunch']);
                    $requestType = "GET";

                    // Execude http request
                    include dirname(__FILE__)."/../../custom_scripts/send_api_request.php";
            
                    // Save image data
                    $storyData = json_decode($result, true);
/*
                    $s = explode(',',trim($instance['tct-storyboxes-storybunch']));
                    foreach($s as $sid){
                        if(trim($sid) != ""){
                            preg_match("/\d+/",trim($sid),$zahl);
                            if(!in_array((int)$zahl[0],$stories)){
                                array_push($stories,(int)$zahl[0]);
                            }
                        }
                    }
                    $query = "SELECT pst.ID FROM ".$wpdb->prefix."posts pst LEFT JOIN ".$wpdb->prefix."postmeta pm1 ON pm1.post_id=pst.ID LEFT JOIN ".$wpdb->prefix."postmeta pm2 ON pm2.post_id=pst.ID LEFT JOIN ".$wpdb->prefix."icl_translations trs ON trs.element_id=pst.ID WHERE pm1.meta_key='tct_story_id' AND pm1.meta_value IN ('".implode("','",$stories)."') AND pm2.meta_key='tct_record_type' AND pm2.meta_value='story' AND trs.source_language_code IS NULL ORDER BY pst.post_date DESC";
                    $dcs = $wpdb->get_results($query,ARRAY_N);
                    $docs = array_column($dcs,0);*/
                }else if(isset($instance['tct-storyboxes-utags']) && is_array($instance['tct-storyboxes-utags']) && sizeof($instance['tct-storyboxes-utags'])>0 || isset($instance['tct-storyboxes-utags']) && trim($instance['tct-storyboxes-utags']) != "" ){
                    if(!is_array($instance['tct-storyboxes-utags'])){
                        $query = "SELECT pst.ID FROM ".$wpdb->prefix."term_relationships trm 
                                    LEFT JOIN ".$wpdb->prefix."icl_translations trs ON trs.element_id=trm.object_id 
                                    JOIN ".$wpdb->prefix."posts pst ON pst.ID=trm.object_id 
                                    WHERE trm.term_taxonomy_id='".(int)trim($instance['tct-storyboxes-utags'])."' AND trs.source_language_code IS NULL
                                    GROUP BY pst.post_parent";
                    }else{
                        $query = "SELECT pst.ID FROM ".$wpdb->prefix."term_relationships trm 
                                    LEFT JOIN ".$wpdb->prefix."icl_translations trs ON trs.element_id=trm.object_id 
                                    JOIN ".$wpdb->prefix."posts pst ON pst.ID=trm.object_id 
                                    WHERE trm.term_taxonomy_id IN('".implode("','",$instance['tct-storyboxes-utags'])."') AND trs.source_language_code IS NULL
                                    GROUP BY pst.post_parent";
                    }
                    if(isset($instance['tct-storyboxes-ltags']) && trim($instance['tct-storyboxes-ltags']) != "" && trim($instance['tct-storyboxes-ltags']) != "-"){
                        $query .= " AND trm.object_id IN (SELECT trm.object_id FROM ".$wpdb->prefix."term_relationships trm LEFT JOIN ".$wpdb->prefix."icl_translations trs ON trs.element_id=trm.object_id WHERE trm.term_taxonomy_id='".$instance['tct-storyboxes-ltags']."' AND trs.source_language_code IS NULL)";
                    }
    
                    $query .= " ORDER BY pst.id DESC ";
    
                    $dcs = $wpdb->get_results($query,ARRAY_N);
                    $docs = array_column($dcs,0);
                }else if(isset($instance['tct-storyboxes-utags']) && is_array($instance['tct-storyboxes-utags']) && sizeof($instance['tct-storyboxes-utags'])<1 || isset($instance['tct-storyboxes-utags']) && trim($instance['tct-storyboxes-utags']) == "" || !isset($instance['tct-storyboxes-utags'])){
                    // Keine U-Tags
                    if(isset($instance['tct-storyboxes-ltags']) && trim($instance['tct-storyboxes-ltags']) != "" && trim($instance['tct-storyboxes-ltags']) != "-"){
                        $query = "SELECT DISTINCT(trm.object_id) FROM ".$wpdb->prefix."term_relationships trm LEFT JOIN ".$wpdb->prefix."icl_translations trs ON trs.element_id=trm.object_id LEFT JOIN ".$wpdb->prefix."postmeta pm1 ON pm1.post_id=trm.object_id LEFT JOIN ".$wpdb->prefix."posts pst ON pst.ID=trm.object_id WHERE trm.term_taxonomy_id='".(int)trim($instance['tct-storyboxes-ltags'])."' AND trs.source_language_code IS NULL AND pm1.meta_key='tct_record_type' AND pm1.meta_value='story'  ORDER BY pst.post_date DESC";
    
                    }else{
    
                    }
                    $dcs = $wpdb->get_results($query,ARRAY_N);
                    $docs = array_column($dcs,0);
                }
                if(isset($instance['tct-storyboxes-cols']) && trim($instance['tct-storyboxes-cols']) != ""){ 
                    $tct_doccols = (int)$instance['tct-storyboxes-cols'];
                }else{
                    $tct_doccols = 4;
                }
                if(is_array($storyData) && sizeof($storyData) > 0){
                    
                    
                    
                    
                    
                   /* $limit = 12;
                    $stand = 0;
                    $portions = array_chunk($storyData, $limit);
                    echo "<div id=\"tct_storyboxidholder_".$myid."\" style=\"display:none;\">\n";
                    $i=0;
                    foreach($portions as $p){
                        echo "<div class=\"tct_sry_".$i."\">".implode(',',$p)."</div>\n";
                        $i++;
                    }
                    echo "</div>\n";*/
                    echo "<div id=\"doc-results_".$myid."\">\n";
                        echo "<div class=\"tableholder\">\n";
                            echo "<div class=\"tablegrid\">\n";
                                echo "<div class=\"section group sepgroup tab\">\n";
                                    foreach($storyData as $story){
                                        if($i<$tct_doccols){ $i++; }else{ $i=1; echo "</div>\n<div class=\"section group tab sepgroup\">\n"; }
                                        
                                            echo  '<div class="col span_1_of_4 collection">';
                                                echo  '<div class="dcholder">';
                                                
                                                    $image = json_decode($story['PreviewImageLink'], true);

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

                                                    echo  "<a class='grid-view-image' href='".home_url()."/documents/story/?story=".$story['StoryId']."'>";
                                                        echo  '<img src='.$gridImageLink.'>';
                                                    echo  "</a>";

                                                    $statusData = array();
                                                    $itemAmount = 0;
                                                    foreach($story['CompletionStatus'] as $status) {
                                                        $itemAmount += $status['Amount'];
                                                    }
                                                    
                                                    $totalPercent = 0;
                                                    foreach($story['CompletionStatus'] as $status) {
                                                        $statusObject = new stdClass;
                                                        $statusObject->Name = $status['Name'];
                                                        $statusObject->ColorCode = $status['ColorCode'];
                                                        $statusObject->ColorCodeGradient = $status['ColorCodeGradient'];
                                                        $statusObject->Amount = (round($status['Amount'] / $itemAmount, 2) * 100);

                                                        array_push($statusData, $statusObject);
                                                        $totalPercent += $statusObject->Amount;
                                                    }

                                                    // Make sure that percent total is 100
                                                    foreach ($statusData as $status) {
                                                        if ($status->Name == "Not Started") {
                                                            if ($totalPercent != 100) {
                                                                $status->Amount += (100 - $totalPercent);
                                                            }
                                                        }
                                                    }
                                                                                            
                                                    echo '<div class="box-progress-bar item-status-chart">';
                                                        echo '<div class="item-status-info-box box-status-bar-info-box">';
                                                            echo '<ul class="item-status-info-box-list">';
                                                                foreach ($statusData as $status) {
                                                                    $percentage = $status->Amount;
                                                                    echo '<li>';
                                                                        echo '<span class="status-info-box-color-indicator" style="background-color:'.$status->ColorCode.';
                                                                                        background-image: -webkit-gradient(linear, left top, left bottom,
                                                                                        color-stop(0, '.$status->ColorCode.'), color-stop(1, '.$status->ColorCodeGradient.'));">';
                                                                        echo '</span>';
                                                                        echo '<span id="progress-bar-overlay-'.str_replace(' ', '-', $status->Name).'-section" class="status-info-box-percentage" style="width: 20%;">';
                                                                            echo $percentage.'%';
                                                                        echo '</span>';
                                                                        echo '<span class="status-info-box-text">';
                                                                            echo $status->Name;
                                                                        echo '</span>';
                                                                    echo '</li>';
                                                                }
                                                            echo '</ul>';
                                                        echo '</div>';

                                                        $CompletedBar = "";
                                                        $ReviewBar = "";
                                                        $EditBar = "";
                                                        $NotStartedBar = "";
                                                        foreach ($statusData as $status) {
                                                            $percentage = $status->Amount;

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
                                                            echo $CompletedBar;
                                                        }
                                                        if ($ReviewBar != "") {
                                                            echo $ReviewBar;
                                                        }
                                                        if ($EditBar != "") {
                                                            echo $EditBar;
                                                        }
                                                        if ($NotStartedBar != "") {
                                                            echo $NotStartedBar;
                                                        }
                                                    echo '</div>';
                                                echo '</div>';

                                                echo  '<div class="">';
                                                    echo  '<h3 class="theme-color">';
                                                        echo  "<a href='".home_url()."/documents/story/?story=".$story['StoryId']."'>";
                                                            echo  $story['dcTitle'];
                                                        echo  "</a>";
                                                    echo  '</h3>';
                                                    /*
                                                    echo  '<div class="search-page-single-result-description">';
                                                        echo  $story['dcDescription'];
                                                    echo  '</div>';*/
                                                    echo  '<span style="display: none">...</span>';
                                                echo  '</div>';
                                                
                                                echo  '<div style="clear:both"></div>';
                                            echo  '</div>';

                                        //include(locate_template('document.php'));
                                        //get_template_part(document);
                                        if($tct_doccols === 4){
                                            if($i==2){ echo "<span class=\"sep\"></span>\n";}
                                        }else if($tct_doccols === 3){
                                            if($i==2){ echo "<span class=\"sep\"></span>\n";}
                                        }
                                    }
                                    wp_reset_postdata();
                                echo "</div>\n";	
                            echo "</div>\n";
                        echo "</div>\n";
    
                        /*
                    if(sizeof($portions) > 1){
                        echo "<a href=\"\" class=\"tct-vio-but load-more-storyboxes\" id=\"tct_storyboxmore_".$myid."\" onclick=\"tct_storybox_getNextTwelve('".$myid."','".((int)$stand+1)."','".$tct_doccols."'); return false;\">"._x('Load more stories','Story-Box Widget','transcribathon')."</a>\n";
                    }*/
                    echo "</div>\n";
                    }
                    echo "<p style=\"display:block; clear:both;\"></p>\n";
                
        
    }
    


?>