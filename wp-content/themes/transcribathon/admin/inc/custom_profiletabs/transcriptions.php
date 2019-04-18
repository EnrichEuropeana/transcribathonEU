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
}
add_shortcode( 'transcription_tab', '_TCT_transcription_tab' );
?>