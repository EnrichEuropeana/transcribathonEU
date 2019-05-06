<?php
/* 
Shortcode: transcription_tab
Description: Creates the transcription profile tab
*/
function _TCT_transcription_tab( $atts ) {  
    echo "<div class=\"section group \">\n";
    
        echo "<div class=\"col span_1_of_5 alg_c\">\n";	
        //$p_totmiles = $wpdb->get_results("SELECT (SUM(miles_account)+SUM(miles_chars)+SUM(miles_review)+SUM(miles_complete)+SUM(miles_sharing)+SUM(miles_message)+SUM(miles_locations)+SUM(miles_enrichements)) AS totalmiles FROM ".$wpdb->prefix."user_miles WHERE userid='".um_profile_id()."'",ARRAY_N);
            echo "<div class=\"number-ball alg_c\">\n";
                echo "<div class=\"number-ball-content\">\n";
                    //echo "<p>".number_format_i18n((int)$p_totmiles[0][0])."</p>";
                    echo "<span>"._x('miles run', 'Transcription-Tab on Profile', 'transcribathon'  )."</span>";
                echo "</div>\n";
            echo "</div>\n";	
        echo "</div>\n";
        echo "<div class=\"col span_1_of_5 alg_c\">\n";				
            //$p_chars = $wpdb->get_results("SELECT sum(amount) FROM ".$wpdb->prefix."user_transcriptionprogress WHERE userid='".um_profile_id()."'",ARRAY_N );
            echo "<div class=\"number-ball\">\n";
                echo "<div class=\"number-ball-content\">\n";
                    //echo "<p>".number_format_i18n((int)$p_chars[0][0])."</p>";
                    echo "<span>"._x('characters', 'Transcription-Tab on Profile', 'transcribathon'  )."</span>";
                echo "</div>\n";
            echo "</div>\n";	
        echo "</div>\n";
        echo "<div class=\"col span_1_of_5 alg_c\">\n";				
            //$p_chars = $wpdb->get_results("SELECT COUNT(*) FROM ".$wpdb->prefix."user_enrichements WHERE e_type='location' AND e_action='new' AND userid='".um_profile_id()."'",ARRAY_N );
            echo "<div class=\"number-ball\">\n";
                echo "<div class=\"number-ball-content\">\n";
                    //echo "<p>".number_format_i18n((int)$p_chars[0][0])."</p>";
                    echo "<span>"._x('locations', 'Transcription-Tab on Profile', 'transcribathon'  )."</span>";
                echo "</div>\n";
            echo "</div>\n";	
        echo "</div>\n";	
        echo "<div class=\"col span_1_of_5 alg_c\">\n";	
            //$p_chars = $wpdb->get_results("SELECT((SELECT COUNT(*) FROM ".$wpdb->prefix."user_enrichements WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag'  OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND userid = '".um_profile_id()."') + (select count(*) as counter FROM ( SELECT 0 as whatever FROM ".$wpdb->prefix."user_enrichements yt WHERE (yt.e_type = 'item-description' AND CHAR_LENGTH(yt.e_note) > 0) AND yt.userid='".um_profile_id()."' GROUP BY yt.docid) sq))",ARRAY_N);
            //$p_chars = $wpdb->get_results("SELECT COUNT(*) as ens FROM ".$wpdb->prefix."user_enrichements WHERE e_type != 'location' AND userid='".um_profile_id()."'",ARRAY_N);
            echo "<div class=\"number-ball\">\n";
                echo "<div class=\"number-ball-content\">\n";
                    //echo "<p>".number_format_i18n((int)$p_chars[0][0])."</p>";
                    echo "<span>"._x('enrichments', 'Transcription-Tab on Profile', 'transcribathon'  )."</span>";
                echo "</div>\n";
            echo "</div>\n";	
        echo "</div>\n";	
        echo "<div class=\"col span_1_of_5\">\n";				
            //$docs = $wpdb->get_results("SELECT count(DISTINCT docid) FROM ".$wpdb->prefix."user_transcriptionprogress WHERE userid='".um_profile_id()."'",ARRAY_N);
            //$docs2 = $wpdb->get_results("SELECT count(DISTINCT docid) FROM ".$wpdb->prefix."user_enrichements WHERE userid='".um_profile_id()."'",ARRAY_N);
            //$docs = $wpdb->get_results("SELECT COUNT(DISTINCT docid) AS transcount,(SELECT COUNT(DISTINCT docid) FROM ".$wpdb->prefix."user_enrichements WHERE userid='".um_profile_id()."' AND docid NOT IN (SELECT DISTINCT docid FROM ".$wpdb->prefix."user_transcriptionprogress WHERE userid='".um_profile_id()."') ) as enrcount FROM ".$wpdb->prefix."user_transcriptionprogress WHERE userid='".um_profile_id()."'",ARRAY_A);
            echo "<div class=\"number-ball alg_c\">\n";
                echo "<div class=\"number-ball-content\">\n";
                    //echo "<p>".number_format_i18n(((int)$docs[0]['transcount']+(int)$docs[0]['enrcount']))."</p>";
                    echo "<span>"._x('documents', 'Transcription-Tab on Profile', 'transcribathon'  )."</span>";
                echo "</div>\n";
            echo "</div>\n";
        echo "</div>\n";
    echo "</div>\n";


    echo "<p>&nbsp;</p>\n<div id=\"personal_chart\">\n";
        echo "<script type=\"text/javascript\">\n";
            //echo "getTCTlinePersonalChart('days','".date('Y-m-d',strtotime("- 1 month",strtotime(date('Y-m-d'))))."','".date('Y-m-d')."','personal_chart','".um_profile_id()."');\n";	
            //$amt = $wpdb->get_results("SELECT SUM(amount) FROM ".$wpdb->prefix."user_transcriptionprogress WHERE userid='".um_profile_id()."' and datum >= '".date('Y-m-')."01' AND datum <= '".date('Y-m-t')."'",ARRAY_N);
            if(trim($amt[0][0]) != "" && (int)$amt[0][0] > 0){
                echo "getTCTlinePersonalChart('days','".date('Y-m-')."01','".date('Y-m-t')."','personal_chart','".um_profile_id()."');\n";
            }else{
                echo "getTCTlinePersonalChart('months','".date('Y-')."01-01','".date('Y-m-t',strtotime(date('Y').'-12-01'))."','personal_chart','".um_profile_id()."');\n";
            }
        echo "</script>\n";
    echo "</div>\n";

    //$docs = $wpdb->get_results("SELECT crh.*,pst.post_title AS title,SUM(crh.amount) AS menge,MAX(crh.datum) as zeitpunkt FROM ".$wpdb->prefix."user_transcriptionprogress crh LEFT JOIN ".$wpdb->prefix."posts pst ON pst.ID = crh.docid WHERE crh.userid='".um_profile_id()."' GROUP BY crh.docid ORDER BY crh.datum DESC",ARRAY_A);
		
	echo "<h2>"._x('Transcribed Documents','Transcription-Tab on Profile', 'transcribathon'  )."</h2>\n";
		echo "<div id=\"doc-results profile\">\n";
            echo "<div class=\"tableholder\">\n";
                echo "<div class=\"tablegrid\">\n";	
                    echo "<div class=\"section group sepgroup tab\">\n";
                        $i=0;
                        /*Set request parameters*/
                        $data = array(
                            'WP_UserId' => get_current_user_id()
                        );
                        $url = network_home_url()."/tp-api/TranscriptionProfile/search;";
                        $requestType = "POST";

                        // Execude http request
                        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                        // Display data
                        $data = json_decode($result, true);
                        if ($data != null) {
                            foreach ($data as $transcription){
                                //var_dump($transcription);
                                if($i>3){ echo "</div>\n<div class=\"section group sepgroup tab\">\n"; $i=0; }
                                echo "<div class=\"column span_1_of_4 collection\">\n";
                                    //$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $doc['docid'] ),'post-thumbnail');
                                    //$c = get_post_custom($doc['docid']);
                                        //echo "<a href=\"/".ICL_LANGUAGE_CODE."/documents/id-".$doc['storyid']."/item-".$doc['itemid']."\">";
                                        echo "<div class=\"dcholder\" style=\"background-image: url(".$transcription['ItemImageLink']."); \"><img src=\"".$transcription['ItemImageLink']."\" alt=\"\" /></div>\n";
                                        echo "<h3 id= \"nopadmod\" class=\"nopad\">".$transcription['ItemTitle']."</h3>\n";
                                        echo "<p id= \"smalladinfo\" class=\"smallinfo\">"._x('Last time','Transcription-Tab on Profile','transcribathon').": ".date_i18n(get_option('date_format'),strtotime($transcription['Timestamp']))."<br />"._x('Amount of characters','Transcription-Tab on Profile','transcribathon').": ".strlen($transcription['Text'])."</p>\n";
                                //echo "</a>\n";
                                //echo "<div class=\"docstate ".$c['tct_transcription_status'][0]."\">".$c['tct_transcription_status'][0]."</div>\n";
                                echo "</div>\n";
                                $i++;
                            }
                        }
                    echo "</div>\n";	
                echo "</div>\n";
            echo "</div>\n";
		echo "</div>\n";
	
	if(is_user_logged_in() &&  get_current_user_id() === 1){	}
			//$docs = $wpdb->get_results("SELECT *,SUM(amount) AS menge,MAX(datum) as zeitpunkt FROM ".$wpdb->prefix."user_transcriptionprogress WHERE userid='".um_profile_id()."' GROUP BY docid ORDER BY datum DESC",ARRAY_A);
			/*$amt = $wpdb->get_results("SELECT SUM(amount) FROM ".$wpdb->prefix."user_transcriptionprogress WHERE userid='".um_profile_id()."' and datum >= '".date('Y-m-')."01' AND datum <= '".date('Y-m-t')."'",ARRAY_N);
			echo "<pre>".print_r($amt,true)."</pre>";*/


}
add_shortcode( 'transcription_tab', '_TCT_transcription_tab' );
?>