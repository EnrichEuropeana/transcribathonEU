<?php
/* 
Shortcode: transcription_tab
Description: Creates the transcription profile tab
*/
function _TCT_transcription_tab( $atts ) {  
    echo "<div class=\"section group \">\n";
        // Set request parameters for image data
        $requestData = array(
            'key' => 'testKey'
        );
        $url = network_home_url()."/tp-api/profileStatistics/".get_current_user_id();
        $requestType = "GET";

        // Execude http request
        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

        // Save image data
        $profileStatistics = json_decode($result, true);
        $profileStatistics = $profileStatistics[0];
    
        echo "<div class=\"column-rgs span_1_of_5 alg_c\">\n";	
           echo "<div class=\"number-ball alg_c\">\n";
                echo "<div class=\"theme-color-background number-ball-content\">\n";
                    echo "<p>".number_format_i18n($profileStatistics['Miles'])."</p>";
                    echo "<span>"._x('miles run', 'Transcription-Tab on Profile', 'transcribathon'  )."</span>";
                echo "</div>\n";
            echo "</div>\n";	
        echo "</div>\n";
        echo "<div class=\"column-rgs span_1_of_5 alg_c\">\n";				
            echo "<div class=\"number-ball\">\n";
                echo "<div class=\"theme-color-background number-ball-content\">\n";
                    echo "<p>".number_format_i18n($profileStatistics['TranscriptionCharacters'])."</p>";
                    echo "<span>"._x('characters', 'Transcription-Tab on Profile', 'transcribathon'  )."</span>";
                echo "</div>\n";
            echo "</div>\n";	
        echo "</div>\n";
        echo "<div class=\"column-rgs span_1_of_5 alg_c\">\n";				
            echo "<div class=\"number-ball\">\n";
                echo "<div class=\"theme-color-background number-ball-content\">\n";
                    echo "<p>".number_format_i18n($profileStatistics['Locations'])."</p>";
                    echo "<span>"._x('locations', 'Transcription-Tab on Profile', 'transcribathon'  )."</span>";
                echo "</div>\n";
            echo "</div>\n";	
        echo "</div>\n";	
        echo "<div class=\"column-rgs span_1_of_5 alg_c\">\n";	
            echo "<div class=\"number-ball\">\n";
                echo "<div class=\"theme-color-background number-ball-content\">\n";
                    echo "<p>".number_format_i18n($profileStatistics['Enrichments'])."</p>";
                    echo "<span>"._x('enrichments', 'Transcription-Tab on Profile', 'transcribathon'  )."</span>";
                echo "</div>\n";
            echo "</div>\n";	
        echo "</div>\n";	
        echo "<div class=\"column-rgs span_1_of_5\">\n";				
            echo "<div class=\"number-ball alg_c\">\n";
                echo "<div class=\"theme-color-background number-ball-content\">\n";
                    echo "<p>".number_format_i18n($profileStatistics['DocumentCount'])."</p>";
                    echo "<span>"._x('documents', 'Transcription-Tab on Profile', 'transcribathon'  )."</span>";
                echo "</div>\n";
            echo "</div>\n";
        echo "</div>\n";
    echo "</div>\n";


    echo "<p>&nbsp;</p>\n<div id=\"personal_chart\">\n";
        echo "<script type=\"text/javascript\">\n";
            if(trim($amt[0][0]) != "" && (int)$amt[0][0] > 0){
                echo "getTCTlinePersonalChart('days','".date('Y-m-')."01','".date('Y-m-t')."','personal_chart','".um_profile_id()."';\n";
            }else{
                echo "getTCTlinePersonalChart('months','".date('Y-')."01-01','".date('Y-m-t',strtotime(date('Y').'-12-01'))."','personal_chart','".um_profile_id()."');\n";
            }
        echo "</script>\n";
    echo "</div>\n";

    //$docs = $wpdb->get_results("SELECT crh.*,pst.post_title AS title,SUM(crh.amount) AS menge,MAX(crh.datum) as zeitpunkt FROM ".$wpdb->prefix."user_transcriptionprogress crh LEFT JOIN ".$wpdb->prefix."posts pst ON pst.ID = crh.docid WHERE crh.userid='".um_profile_id()."' GROUP BY crh.docid ORDER BY crh.datum DESC",ARRAY_A);
		
    /*Set request parameters*/
    $url = network_home_url()."/tp-api/transcriptionProfile?WP_UserId=".um_profile_id();
    $requestType = "GET";

    // Execude http request
    include dirname(__FILE__)."/../custom_scripts/send_api_request.php";
    
    // Display data
    $documents = json_decode($result, true);

	echo "<h2>"._x('Transcribed Documents','Transcription-Tab on Profile', 'transcribathon'  )."</h2>\n";
		echo "<div id=\"doc-results profile\">\n";
            echo "<div class=\"tableholder\">\n";
                echo "<div class=\"tablegrid\">\n";	
                    echo "<div class=\"section group sepgroup tab\">\n";
                        $i=0;
                        if ($documents != null) {
                            foreach ($documents as $document){
                                //var_dump($transcription);
                                if($i>3){ echo "</div>\n<div class=\"section group sepgroup tab\">\n"; $i=0; }
                                echo "<div class=\"column span_1_of_4 collection\">\n";
                                    //$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $doc['docid'] ),'post-thumbnail');
                                    //$c = get_post_custom($doc['docid']);
                                        echo "<a href=\"https://europeana.fresenia.man.poznan.pl/documents/story/item?item=".$document['ItemId']."\">";
                                        echo "<div class=\"dcholder\" style=\"background-image: url(".$document['ItemImageLink']."); \"><img src=\"".$document['ItemImageLink']."\" alt=\"\" /></div>\n";
                                        echo "<h3 id= \"nopadmod\" class=\"nopad\">".$document['ItemTitle']."</h3>\n";
                                        echo "<p id= \"smalladinfo\" class=\"smallinfo\">";
                                        echo "Last time: ".date_i18n(get_option('date_format'),strtotime($document['Timestamp']))."<br />";
                                        switch ($document['ScoreType']) {
                                            case "Transcription":
                                                echo "Characters: ".$document['Amount']."</p>\n";
                                                break;
                                            case "Location":
                                                echo "Locations: ".$document['Amount']."</p>\n";
                                                break;
                                            case "Enrichment":
                                                echo "Enrichments: ".$document['Amount']."</p>\n";
                                                break;
                                        }

                                echo "</a>\n";
                                echo "<div class=\"docstate ".$document['CompletionStatus']."\">".$document['CompletionStatus']."</div>\n";
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