<?php
global $wpdb;
$myid = uniqid(rand()).date('YmdHis');
$base = 0;

if ( ! is_admin() ) {

   
                if($instance['tct-storyboxes-headline'] != ""){ echo "<h1>".str_replace("\n","<br />",$instance['tct-storyboxes-headline'])."</h1>\n"; }
                $stories = array();
                $docs = array();
                if(isset($instance['tct-storyboxes-storybunch']) && trim($instance['tct-storyboxes-storybunch']) != ""){ 
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
                    $docs = array_column($dcs,0);
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
                if(is_array($docs) && sizeof($docs) > 0){
                    
                    
                    
                    
                    
                    $limit = 12;
                    $stand = 0;
                    $portions = array_chunk($docs, $limit);
                    echo "<div id=\"tct_storyboxidholder_".$myid."\" style=\"display:none;\">\n";
                    $i=0;
                    foreach($portions as $p){
                        echo "<div class=\"tct_sry_".$i."\">".implode(',',$p)."</div>\n";
                        $i++;
                    }
                    echo "</div>\n";
                    echo "<div id=\"doc-results_".$myid."\">\n";
                        echo "<div class=\"tableholder\">\n";
                            echo "<div class=\"tablegrid\">\n";
                                echo "<div class=\"section group sepgroup tab\">\n";
                                    $my_args = array('post_type'=>'documents','numberposts'=>12,'paged'=>true,'order_by'=>'post_date','order'=>'DESC',
                                        'post__in' => $portions[$stand],
                                    );
                                    $str = get_posts($my_args);
                                    global $post;
                                    $i=0;
                                    foreach($str as $post){
                                        if($i<$tct_doccols){ $i++; }else{ $i=1; echo "</div>\n<div class=\"section group tab sepgroup\">\n"; }
                                        include(locate_template('document.php'));
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
    
                    if(sizeof($portions) > 1){
                        echo "<a href=\"\" class=\"tct-vio-but load-more-storyboxes\" id=\"tct_storyboxmore_".$myid."\" onclick=\"tct_storybox_getNextTwelve('".$myid."','".((int)$stand+1)."','".$tct_doccols."'); return false;\">"._x('Load more stories','Story-Box Widget','transcribathon')."</a>\n";
                    }
                    echo "</div>\n";
                    }
                    echo "<p style=\"display:block; clear:both;\"></p>\n";
                
        
    }
    


?>