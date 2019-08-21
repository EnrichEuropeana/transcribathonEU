<?php
global $wpdb;
$myid = uniqid(rand()).date('YmdHis');
$base = 0;
// Limit
if(isset($instance['tct-top-transcribers-amount']) && trim($instance['tct-top-transcribers-amount']) != "" && (int)$instance['tct-top-transcribers-amount'] > 0){ $limit = (int)$instance['tct-top-transcribers-amount'];}else{$limit = 10;}
// Subject && Settings
if(isset($instance['tct-top-transcribers-subject']) && trim($instance['tct-top-transcribers-subject']) == "teams"){ 
	// TEAMS
	$subject = "teams"; 
	if(isset($instance['tct-top-transcribers-settings-teams']['tct-top-transcribers-kind']) && trim($instance['tct-top-transcribers-settings-teams']['tct-top-transcribers-kind']) == "campaign"){ if(isset($instance['tct-top-transcribers-settings-teams']['tct-top-transcribers-campaign']) && trim($instance['tct-top-transcribers-settings-teams']['tct-top-transcribers-campaign']) != ""){ $kind = "campaign"; $cp = (int)trim($instance['tct-top-transcribers-settings-teams']['tct-top-transcribers-campaign']); }else{ $kind = "all"; $cp = ""; } }else{ $kind = "all"; $cp = ""; }
}else{
	// INDIVIDUALS
	$subject = "individuals"; 
	if(isset($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-kind']) && trim($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-kind']) == "campaign"){ if(isset($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-campaign']) && trim($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-campaign']) != ""){ $kind = "campaign"; $cp = (int)trim($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-campaign']); }else{ $kind = "all"; $cp = ""; } }else{ $kind = "all"; $cp = ""; }
}



if($instance['tct-top-transcribers-headline'] != ""){ echo "<h1>".str_replace("\n","<br />",$instance['tct-top-transcribers-headline'])."</h1>\n"; }

	if($kind === "campaign"){
		/*
		if($subject === "teams"){ // team
			$alltops = $wpdb->get_results("SELECT COUNT(DISTINCT prg.teamid) AS total FROM ".$wpdb->prefix."campaign_transcriptionprogress prg JOIN ".$wpdb->prefix."teams t ON t.team_id=prg.teamid WHERE prg.campaignid='".$cp."'",ARRAY_A );
			if((int)$alltops[0]['total'] <= $base){
				$base = (floor(((int)$alltops[0]['total']-1) / $limit)) * $limit;
			}
				
			if ($cp == 40) {
				$query = "SELECT prg.teamid,SUM(prg.amount) AS teamamnt, tm.team_title as post_title, 'teams' as post_type, LOWER(REPLACE((SELECT post_title), ' ', '-')) as post_name, 
						(SELECT COUNT(DISTINCT utm.user_id) FROM ".$wpdb->prefix."user_teams utm WHERE utm.team_id=prg.teamid) AS members, 
						(SUM(prg.amount)/(SELECT members)) AS relamount, 
						(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid) AS locations, 
						(SELECT
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements
							  WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag' OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND teamid = prg.teamid)
							+ (SELECT COUNT(DISTINCT docid) FROM ".$wpdb->prefix."campaign_enrichements yt WHERE yt.e_type = 'item-description' AND yt.e_note is not null AND yt.teamid=prg.teamid)
						) AS enrichements, 
						FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						) AS c_miles, 
						FLOOR((SELECT enrichements)/20) AS e_miles, 
						FLOOR(
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid)/10
						) AS l_miles, 
						(FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						)) + (SELECT e_miles) + (SELECT l_miles) AS totalmiles, 
						((FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						)) + (SELECT e_miles) + (SELECT l_miles)) / (SELECT members) AS relmiles 
						FROM ".$wpdb->prefix."campaign_transcriptionprogress prg 
							JOIN ".$wpdb->prefix."teams tm
							ON tm.team_id = prg.teamid 
						WHERE prg.campaignid=".$cp." AND prg.datum <= '2018-12-06 14:15:00'
						GROUP BY prg.teamid 
						ORDER BY relmiles DESC, relamount DESC
						LIMIT ".$base.",".$limit;
			}
			elseif ($cp == 41) {
				$query = "SELECT prg.teamid,SUM(prg.amount) AS teamamnt, tm.team_title as post_title, 'teams' as post_type, LOWER(REPLACE((SELECT post_title), ' ', '-')) as post_name, 
						(SELECT COUNT(DISTINCT utm.user_id) FROM ".$wpdb->prefix."user_teams utm WHERE utm.team_id=prg.teamid) AS members, 
						(SUM(prg.amount)/(SELECT members)) AS relamount, 
						(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid) AS locations, 
						(SELECT
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements
							  WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag' OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND teamid = prg.teamid)
							+ (SELECT COUNT(DISTINCT docid) FROM ".$wpdb->prefix."campaign_enrichements yt WHERE yt.e_type = 'item-description' AND yt.e_note is not null AND yt.teamid=prg.teamid)
						) AS enrichements, 
						FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						) AS c_miles, 
						FLOOR((SELECT enrichements)/20) AS e_miles, 
						FLOOR(
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid)/10
						) AS l_miles, 
						(FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						)) + (SELECT e_miles) + (SELECT l_miles) AS totalmiles, 
						((FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						)) + (SELECT e_miles) + (SELECT l_miles)) / (SELECT members) AS relmiles 
						FROM ".$wpdb->prefix."campaign_transcriptionprogress prg 
							JOIN ".$wpdb->prefix."teams tm
							ON tm.team_id = prg.teamid 
						WHERE prg.campaignid=".$cp." AND prg.datum <= '2018-12-06 15:30:00' AND prg.datum > '2018-12-06 14:45:00'
						GROUP BY prg.teamid 
						ORDER BY relmiles DESC, relamount DESC
						LIMIT ".$base.",".$limit;
			} elseif ($cp == 42) {
				$query = "SELECT prg.teamid,SUM(prg.amount) AS teamamnt, tm.team_title as post_title, 'teams' as post_type, LOWER(REPLACE((SELECT post_title), ' ', '-')) as post_name, 
						(SELECT COUNT(DISTINCT utm.user_id) FROM ".$wpdb->prefix."user_teams utm WHERE utm.team_id=prg.teamid) AS members, 
						(SUM(prg.amount)/(SELECT members)) AS relamount, 
						(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid) AS locations, 
						(SELECT
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements
							  WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag' OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND teamid = prg.teamid)
							+ (SELECT COUNT(DISTINCT docid) FROM ".$wpdb->prefix."campaign_enrichements yt WHERE yt.e_type = 'item-description' AND yt.e_note is not null AND yt.teamid=prg.teamid)
						) AS enrichements, 
						FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						) AS c_miles, 
						FLOOR((SELECT enrichements)/20) AS e_miles, 
						FLOOR(
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid)/10
						) AS l_miles, 
						(FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						)) + (SELECT e_miles) + (SELECT l_miles) AS totalmiles, 
						((FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						)) + (SELECT e_miles) + (SELECT l_miles)) / (SELECT members) AS relmiles 
						FROM ".$wpdb->prefix."campaign_transcriptionprogress prg 
							JOIN ".$wpdb->prefix."teams tm
							ON tm.team_id = prg.teamid 
						WHERE prg.campaignid=".$cp." AND prg.datum <= '2018-12-06 16:45:00' AND prg.datum > '2018-12-06 16:00:00'
						GROUP BY prg.teamid 
						ORDER BY relmiles DESC, relamount DESC
						LIMIT ".$base.",".$limit;
			} else {
				$query = "SELECT prg.teamid,SUM(prg.amount) AS teamamnt, tm.team_title as post_title, 'teams' as post_type, LOWER(REPLACE((SELECT post_title), ' ', '-')) as post_name, 
							(SELECT COUNT(DISTINCT utm.user_id) FROM ".$wpdb->prefix."user_teams utm WHERE utm.team_id=prg.teamid) AS members, 
							(SUM(prg.amount)/(SELECT members)) AS relamount, 
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid) AS locations, 
							(SELECT
								(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements
								  WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag' OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND teamid = prg.teamid)
								+ (SELECT COUNT(DISTINCT docid) FROM ".$wpdb->prefix."campaign_enrichements yt WHERE yt.e_type = 'item-description' AND yt.e_note is not null AND yt.teamid=prg.teamid)
							) AS enrichements, 
							FLOOR(
								(CASE 
									WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
									WHEN SUM(prg.amount) >=10000 THEN 20
									WHEN SUM(prg.amount) >=7500 THEN 15
									WHEN SUM(prg.amount) >=5000 THEN 10
									WHEN SUM(prg.amount) >=1000 THEN 5
									WHEN SUM(prg.amount) >=500 THEN 3 
									WHEN SUM(prg.amount) >=200 THEN 1 
									ELSE 0
								 END)
							) AS c_miles, 
							FLOOR((SELECT enrichements)/20) AS e_miles, 
							FLOOR(
								(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid)/10
							) AS l_miles, 
							(FLOOR(
								(CASE 
									WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
									WHEN SUM(prg.amount) >=10000 THEN 20
									WHEN SUM(prg.amount) >=7500 THEN 15
									WHEN SUM(prg.amount) >=5000 THEN 10
									WHEN SUM(prg.amount) >=1000 THEN 5
									WHEN SUM(prg.amount) >=500 THEN 3 
									WHEN SUM(prg.amount) >=200 THEN 1 
									ELSE 0
								 END)
							)) + (SELECT e_miles) + (SELECT l_miles) AS totalmiles, 
							((FLOOR(
								(CASE 
									WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
									WHEN SUM(prg.amount) >=10000 THEN 20
									WHEN SUM(prg.amount) >=7500 THEN 15
									WHEN SUM(prg.amount) >=5000 THEN 10
									WHEN SUM(prg.amount) >=1000 THEN 5
									WHEN SUM(prg.amount) >=500 THEN 3 
									WHEN SUM(prg.amount) >=200 THEN 1 
									ELSE 0
								 END)
							)) + (SELECT e_miles) + (SELECT l_miles)) / (SELECT members) AS relmiles 
							FROM ".$wpdb->prefix."campaign_transcriptionprogress prg 
								JOIN ".$wpdb->prefix."teams tm
								ON tm.team_id = prg.teamid 
							WHERE prg.campaignid=".$cp."
							GROUP BY prg.teamid 
							ORDER BY relmiles DESC, relamount DESC
							LIMIT ".$base.",".$limit;
			}
			$topusrs = $wpdb->get_results($query,ARRAY_A);
			
		}else{ // Invdl
			$alltops = $wpdb->get_results("SELECT COUNT(DISTINCT prg.userid) AS total FROM ".$wpdb->prefix."campaign_transcriptionprogress prg JOIN ".$wpdb->prefix."users usr ON usr.ID=prg.userid WHERE prg.campaignid='".$cp."'",ARRAY_A );
				if((int)$alltops[0]['total'] <= $base){
					$base = (floor(((int)$alltops[0]['total']-1) / $limit)) * $limit;
				}
				$query = "SELECT prg.userid,prg.campaignid,
						(
						SELECT GROUP_CONCAT(tShort) AS shortname 
						FROM ".$wpdb->prefix."teams t 
						JOIN ".$wpdb->prefix."user_teams ut 
						ON t.team_id = ut.team_id WHERE ut.user_id = prg.userid
						) AS teams,
						SUM(prg.amount)AS useramnt,
						usr.display_name,
						(SELECT
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements
							  WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag' OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND userid = prg.userid)
							+ (SELECT COUNT(DISTINCT docid) FROM ".$wpdb->prefix."campaign_enrichements yt WHERE yt.e_type = 'item-description' AND yt.e_note is not null AND yt.teamid=prg.teamid)
						) AS enrichements, 
						FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						) AS c_miles, 
						FLOOR((SELECT enrichements)/20) AS e_miles, 
						FLOOR(
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid)/10
						) AS l_miles, 
						(FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						)) + (SELECT e_miles) + (SELECT l_miles) AS umiles, 
						(
						SELECT SUM(ttl.amount) 
						FROM ".$wpdb->prefix."user_transcriptionprogress ttl 
						WHERE ttl.userid=prg.userid
						) AS totalchars,
						(
						SELECT COUNT(*) 
						FROM CSMcH_campaign_enrichements cenr 
						WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.userid=prg.userid
						) AS locations
						FROM ".$wpdb->prefix."campaign_transcriptionprogress prg 
						LEFT JOIN CSMcH_users usr 
						ON usr.ID=prg.userid 
						WHERE prg.campaignid='".$cp."' and prg.userid != 1802
						GROUP BY prg.userid 
						ORDER BY useramnt DESC
						LIMIT ".$base.",".$limit;
			
			if(is_user_logged_in() && get_current_user_id() == 1){	
			//$dquery = "SELECT prg.teamid,SUM(prg.amount) AS teamamnt, tm.post_title, tm.post_type, tm.post_name, (SELECT COUNT(DISTINCT utm.user_id) FROM ".$wpdb->prefix."user_teams utm WHERE utm.team_id=prg.teamid) AS members, ( SUM(prg.amount)/ (SELECT COUNT(DISTINCT utm.user_id) FROM ".$wpdb->prefix."user_teams utm WHERE utm.team_id=prg.teamid)) AS relamount, (SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid) AS locations, ((SELECT((SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag' OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND teamid = prg.teamid) ))+(select count(DISTINCT docid) FROM ".$wpdb->prefix."campaign_enrichements yt WHERE yt.e_type = 'item-description' AND CHAR_LENGTH(yt.e_note) > 0 AND yt.teamid=prg.teamid)) AS enrichements , FLOOR((CASE WHEN SUM(prg.amount) > 199 AND SUM(prg.amount) < 500 THEN 1 WHEN SUM(prg.amount) > 499 AND SUM(prg.amount) < 1000 THEN 3 WHEN SUM(prg.amount) > 999 AND SUM(prg.amount) < 5000 THEN 5 WHEN SUM(prg.amount) > 4999 AND SUM(prg.amount) < 7500 THEN 10 WHEN SUM(prg.amount) > 7499 AND SUM(prg.amount) < 10000 THEN 15 WHEN SUM(prg.amount) > 9999 AND SUM(prg.amount) < 11000 THEN 20 ELSE (20 + (((SUM(prg.amount)-10000)/1000)*7)) END)) AS c_miles, FLOOR(((SELECT((SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag' OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND teamid = prg.teamid) ))+(select count(DISTINCT docid) FROM ".$wpdb->prefix."campaign_enrichements yt WHERE yt.e_type = 'item-description' AND CHAR_LENGTH(yt.e_note) > 0 AND yt.teamid=prg.teamid))/20) AS e_miles, FLOOR((SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid)/10) AS l_miles, (FLOOR((CASE WHEN SUM(prg.amount) > 199 AND SUM(prg.amount) < 500 THEN 1 WHEN SUM(prg.amount) > 499 AND SUM(prg.amount) < 1000 THEN 3 WHEN SUM(prg.amount) > 999 AND SUM(prg.amount) < 5000 THEN 5 WHEN SUM(prg.amount) > 4999 AND SUM(prg.amount) < 7500 THEN 10 WHEN SUM(prg.amount) > 7499 AND SUM(prg.amount) < 10000 THEN 15 WHEN SUM(prg.amount) > 9999 AND SUM(prg.amount) < 11000 THEN 20 ELSE (20 + (((SUM(prg.amount)-10000)/1000)*7)) END)) + FLOOR(((SELECT((SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag' OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND teamid = prg.teamid) ))+(select count(DISTINCT docid) FROM ".$wpdb->prefix."campaign_enrichements yt WHERE yt.e_type = 'item-description' AND CHAR_LENGTH(yt.e_note) > 0 AND yt.teamid=prg.teamid))/20) + FLOOR((SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid)/10)) AS totalmiles, ((FLOOR((CASE WHEN SUM(prg.amount) > 199 AND SUM(prg.amount) < 500 THEN 1 WHEN SUM(prg.amount) > 499 AND SUM(prg.amount) < 1000 THEN 3 WHEN SUM(prg.amount) > 999 AND SUM(prg.amount) < 5000 THEN 5 WHEN SUM(prg.amount) > 4999 AND SUM(prg.amount) < 7500 THEN 10 WHEN SUM(prg.amount) > 7499 AND SUM(prg.amount) < 10000 THEN 15 WHEN SUM(prg.amount) > 9999 AND SUM(prg.amount) < 11000 THEN 20 ELSE (20 + (((SUM(prg.amount)-10000)/1000)*7)) END)) + FLOOR(((SELECT((SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag' OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND teamid = prg.teamid) ))+(select count(DISTINCT docid) FROM ".$wpdb->prefix."campaign_enrichements yt WHERE yt.e_type = 'item-description' AND CHAR_LENGTH(yt.e_note) > 0 AND yt.teamid=prg.teamid))/20) + FLOOR((SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid)/10))/(SELECT COUNT(DISTINCT utm.user_id) FROM ".$wpdb->prefix."user_teams utm WHERE utm.team_id=prg.teamid) ) AS relmiles FROM ".$wpdb->prefix."campaign_transcriptionprogress prg LEFT JOIN ".$wpdb->prefix."posts tm ON tm.ID = prg.teamid WHERE prg.campaignid='".$cp."' GROUP BY prg.teamid ORDER BY relmiles DESC LIMIT ".$base.",".$limit;
						
			//echo "debug<br />".$query;			
						
			}
			
			
			$topusrs = $wpdb->get_results($query,ARRAY_A);
		}
		*/
	}else{
		if($subject === "teams"){  // team
			/*
			$alltops = $wpdb->get_results("SELECT COUNT(DISTINCT prg.teamid) AS total FROM ".$wpdb->prefix."team_transcriptionprogress prg JOIN ".$wpdb->prefix."teams t ON t.team_id=prg.teamid",ARRAY_A );
			if((int)$alltops[0]['total'] <= $base){
				$base = (floor(((int)$alltops[0]['total']-1) / $limit)) * $limit;
			}
			$query = "SELECT prg.teamid,SUM(prg.amount) AS teamamnt, tm.team_title as post_title, 'teams' as post_type, LOWER(REPLACE((SELECT post_title), ' ', '-')) as post_name,
						(SELECT COUNT(DISTINCT utm.user_id) 
						FROM ".$wpdb->prefix."user_teams utm 
						WHERE utm.team_id=prg.teamid) AS members, 
						ROUND(( SUM(prg.amount)/(SELECT members)),2) AS relamount 
					FROM ".$wpdb->prefix."team_transcriptionprogress prg 
						JOIN ".$wpdb->prefix."teams tm ON tm.team_id = prg.teamid 
					GROUP BY prg.teamid 
					ORDER BY relamount DESC 
					LIMIT ".$base.",".$limit;
			$topusrs = $wpdb->get_results($query,ARRAY_A);
			*/
		}else{ // Invdl
			
			// Set request parameters for image data
			$url = home_url()."/tp-api/rankings/userCount";
			$requestType = "GET";

			// Execude http request
			include TCT_THEME_DIR_PATH."admin/inc/custom_scripts/send_api_request.php";

			// Save image data
			$alltops = json_decode($result, true);
			if((int)$alltops <= $base){
				$base = (floor(((int)$alltops-1) / $limit)) * $limit;
			}
			
			// Set request parameters for image data
			$url = home_url()."/tp-api/rankings?offset=".$base."&limit=".$limit;
			$requestType = "GET";

			// Execude http request
			include TCT_THEME_DIR_PATH."admin/inc/custom_scripts/send_api_request.php";

			// Save image data
			$topusrs = json_decode($result, true);
		}
	}
	
	echo "<div id=\"tu_list_".$myid."\">\n";
		if(sizeof($topusrs)>0){
			echo "<ul class=\"topusers\">\n";
			if($subject === "teams"){
				/*
				$i=1;	
				foreach($topusrs as $team){
					echo "<li class=\"p".$i."\">";
					echo "<div class=\"tct-user-banner\"></div>\n"; 
					if($kind === "campaign"){
						
						$miles = "<span class=\"milage\">".sprintf( esc_html( _n( '%s mile per member', '%s miles per member', (float)$team['relmiles'], 'transcribathon'  ) ), number_format_i18n($team['relmiles'],2))."</span>\n";
						$miles2 = "<span class=\"chars\">".sprintf( esc_html( _n( '%s mile in this campaign', '%s total miles in this campaign', (int)$team['totalmiles'], 'transcribathon'  ) ), number_format_i18n((int)$team['totalmiles']))."</span>\n";
						$chars = "<span class=\"chars\">".sprintf( esc_html( _n( '%s character', '%s characters', (int)$team['teamamnt'], 'transcribathon'  ) ), number_format_i18n((int)$team['teamamnt']))."</span>\n";
						$locs = "<span class=\"chars\">".sprintf( esc_html( _n( '%s location', '%s locations', (int)$team['locations'], 'transcribathon'  ) ), number_format_i18n((int)$team['locations']))."</span>\n";
						$enrs = "<span class=\"chars\">".sprintf( esc_html( _n( '%s enrichment', '%s enrichments', (int)$team['enrichements'], 'transcribathon'  ) ), number_format_i18n((int)$team['enrichements']))."</span>\n";
						echo "<span class=\"rang\">".$i."</span><h2><a href=\"/".ICL_LANGUAGE_CODE."/".$team['post_type']."/".$team['post_name']."\">".$team['post_title']."</a></h2><p>".$miles." | ".$miles2." <br /><span class=\"chars\">"._x('Achievements in this campaign','top-list','transcribathon').":</span><br />".$chars." | ".$locs." | ".$enrs."</p></li>\n";
						
						
					}else{
						$miles = "<span class=\"milage\">".sprintf( esc_html( _n( '%s Character per member', '%s Characters per member', (float)$team['relamount'], 'transcribathon'  ) ), number_format_i18n((float)$team['relamount']))."</span>\n";
						$chars = "<span class=\"chars\">".sprintf( esc_html( _n( '%s Character in total', '%s Characters in total', (int)$team['teamamnt'], 'transcribathon'  ) ), number_format_i18n((int)$team['teamamnt']))."</span>\n";
						echo "<span class=\"rang\">".$i."</span><h2><a href=\"/".ICL_LANGUAGE_CODE."/".$team['post_type']."/".$team['post_name']."\">".$team['post_title']."</a></h2><p>".$miles." | ".$chars."</p></li>\n";
					}
					$i++;
				}*/
			}else{
				$i=1;	
				foreach($topusrs as $usr){
					$aut = get_user_by('ID',$usr['UserId']);
					um_fetch_user( $usr['UserId']);
					echo "<li class=\"p".$i."\">";
					echo "<div class=\"tct-user-banner ".um_user('role')."\">".ucfirst(um_user('role'))."</div>\n"; 
					/*
					$acs = $wpdb->get_results("SELECT ac.*,uc.campaign_title,CASE ac.placing WHEN '1' then uc.campaign_badge_1 WHEN '2' then uc.campaign_badge_2 WHEN '3' then uc.campaign_badge_3 END AS badge FROM ".$wpdb->prefix."user_achievments ac LEFT JOIN ".$wpdb->prefix."user_campaigns uc ON uc.id=ac.campaign WHERE ac.userid='".$usr['userid']."'",ARRAY_A);
					if(sizeof($acs)>0){
						echo "<div class=\"tct_tt-achievments\">\n"; 
						foreach($acs as $ac){
							echo "<div title=\"".$ac['campaign_title']."\"class=\"".$ac['badge']."\"></div>\n";
						}
						echo "</div>\n";
					}
					*/
					if($kind === "campaign"){
						/*
						if(isset($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams']) && trim($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams']) == "1" && trim($usr['teams']) != ""){$temm = " (".str_replace(",",", ",$usr['teams']).")";}else{ $temm = "";}
						$miles = "<span class=\"milage\">".sprintf( esc_html( _n( '%s Mile in this campaign', '%s Miles in this campaign', (int)$usr['umiles'], 'transcribathon'  ) ), number_format_i18n((int)$usr['umiles']))."</span>\n";
						$charsc = "<span class=\"chars\"><strong>".sprintf( esc_html( _n( '%s Character in this campaign', '%s Characters in this campaign', (int)$usr['useramnt'], 'transcribathon'  ) ), number_format_i18n((int)$usr['useramnt']))."</strong></span>\n";
						$chars = "<span class=\"chars\">".sprintf( esc_html( _n( '%s Character', '%s Characters', (int)$usr['totalchars'], 'transcribathon'  ) ), number_format_i18n((int)$usr['totalchars']))."</span>\n";
						echo "<span class=\"rang\">".$i."</span><h2><a href=\"/".ICL_LANGUAGE_CODE."/user/".$aut->user_nicename."/\">".um_user('display_name')."</a><span class=\"teammem\">".$temm."</span></h2><p>".$charsc."</p><p><p>".$miles." | ".$chars."</p></p></li>\n";
					*/
					}else{
						if(isset($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams']) && trim($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams']) == "1" && trim($usr['teams']) != ""){$temm = " (".str_replace(",",", ",$usr['teams']).")";}else{ $temm = "";}
						$miles = "<span class=\"milage\">".sprintf( esc_html( _n( '%s Mile', '%s Miles', (int)$usr['Miles'], 'transcribathon'  ) ), number_format_i18n((int)$usr['Miles']))."</span>\n";
						$chars = "<span class=\"chars\">".sprintf( esc_html( _n( '%s Character', '%s Characters', (int)$usr['TranscriptionCharacters'], 'transcribathon'  ) ), number_format_i18n((int)$usr['TranscriptionCharacters']))."</span>\n";
						if ($aut != null) {
							echo "<span class=\"rang\">".$i."</span><h2><a href=\"/user/".$aut->user_nicename."/\">".um_user('display_name')."</a><span class=\"teammem\">".$temm."</span></h2><p>".$miles." | ".$chars."</p></li>\n";
						}
						else {
							echo "<span class=\"rang\">".$i."</span><h2><a href=\"Placeholder User\">Placeholder User</a><span class=\"teammem\">".$temm."</span></h2><p>".$miles." | ".$chars."</p></li>\n";
						}
					}
					$i++;
				}
			}
			echo "</ul>\n";
		}else{
			echo "<p>".$instance['tct-top-transcribers-nothingtoshow']."</p>";
			
		}
	echo "</div>\n"; // #tu_list...
	$showttmenu = 0;
	echo "<div id=\"ttnav_".$myid."\" class=\"ttnav ".$subject."\" style='height:50px'>\n";
	$menu = "<ul>\n";
	if($base>0){
		if(((int)$base-(int)$limit)<0){$newb = 0; }else{ $newb=((int)$base-(int)$limit); }
		$menu .= "<li class=\"ttprev\" style='float:left'><a href=\"\" onclick=\"getMoreTops('".$myid."','".$newb."','".$limit."','".$kind."','".$cp."','".$subject."','".(int)trim($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams'])."'); return false;\">"._x('Previous', 'Top-Transcribers-Slider-Widget (frontentd)','transcribathon')."</a></li>\n";
		$showttmenu++;
	}else{
		$prev = "";
	}
	
	if((int)$alltops > ($base+$limit)){
		$menu .= "<li class=\"ttnext\" style='float:right'><a href=\"\" onclick=\"getMoreTops('".$myid."','".((int)$base+(int)$limit)."','".$limit."','".$kind."','".$cp."','".$subject."','".(int)trim($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams'])."'); return false;\">"._x('Next', 'Top-Transcribers-Slider-Widget (frontentd)','transcribathon')."</a></li>\n";
		$showttmenu++;
	}else{
		$next = "";
	}
	
	$menu .= "<div style='text-align:center'><label>Go to Page <input type='text' name='page_input' id='page_input_".$subject."' style='width:40px'></label>
				<button id='goto_".$subject."' style='font-size:16px; font-weight:bold; padding:7px; margin-left:5px;' 
					onclick=\"getMoreTopsPage('".$myid."','".$limit."','".$kind."','".$cp."','".$subject."','".(int)trim($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams'])."'); return false;\">GO</button>
					</br><p style='color:red; display:none;' id='pageWarning_".$subject."'>Please enter a number</p></div>";

	if($showttmenu > 0){
		echo $menu."</ul>\n";
	}
	//Create loading wheel
	echo '<div id="top-transcribers-spinner" class="spinner" style="float:right; display:none;"></div>';
	echo "</div>\n"; // #ttnav_...
	
	//Show own rank below ranking
	if($subject != "teams"){
		if($kind === "campaign"){
			$query = "SELECT prg.userid,prg.campaignid,
						(
						SELECT GROUP_CONCAT(tShort) AS shortname 
						FROM ".$wpdb->prefix."teams t 
						JOIN ".$wpdb->prefix."user_teams ut 
						ON t.team_id = ut.team_id WHERE ut.user_id = prg.userid
						) AS teams,
						SUM(prg.amount)AS useramnt,
						usr.display_name,
						(SELECT
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements
							  WHERE (e_type='keywords' OR e_type='language-tag' OR e_type = 'theatre-tag' OR e_type = 'additional-source'  OR e_type='overall-category' OR e_type='document-tag') AND userid = prg.userid)
							+ (SELECT COUNT(DISTINCT docid) FROM ".$wpdb->prefix."campaign_enrichements yt WHERE yt.e_type = 'item-description' AND yt.e_note is not null AND yt.teamid=prg.teamid)
						) AS enrichements, 
						FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						) AS c_miles, 
						FLOOR((SELECT enrichements)/20) AS e_miles, 
						FLOOR(
							(SELECT COUNT(*) FROM ".$wpdb->prefix."campaign_enrichements cenr WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.teamid=prg.teamid)/10
						) AS l_miles, 
						(FLOOR(
							(CASE 
								WHEN SUM(prg.amount) >=11000 THEN (20 + (((SUM(prg.amount)-10000)/1000)*7))
								WHEN SUM(prg.amount) >=10000 THEN 20
								WHEN SUM(prg.amount) >=7500 THEN 15
								WHEN SUM(prg.amount) >=5000 THEN 10
								WHEN SUM(prg.amount) >=1000 THEN 5
								WHEN SUM(prg.amount) >=500 THEN 3 
								WHEN SUM(prg.amount) >=200 THEN 1 
								ELSE 0
							 END)
						)) + (SELECT e_miles) + (SELECT l_miles) AS umiles, 
						(
						SELECT SUM(ttl.amount) 
						FROM ".$wpdb->prefix."user_transcriptionprogress ttl 
						WHERE ttl.userid=prg.userid
						) AS totalchars,
						(
						SELECT COUNT(*) 
						FROM CSMcH_campaign_enrichements cenr 
						WHERE cenr.e_type='location' AND cenr.e_action ='new' AND cenr.userid=prg.userid
						) AS locations
						FROM ".$wpdb->prefix."campaign_transcriptionprogress prg 
						LEFT JOIN CSMcH_users usr 
						ON usr.ID=prg.userid 
						WHERE prg.campaignid='".$cp."'
						GROUP BY prg.userid 
						ORDER BY umiles DESC, useramnt DESC";
			$topusrs = $wpdb->get_results($query,ARRAY_A);
		}
		else {
			$query = "SELECT prg.userid,
						(
						SELECT GROUP_CONCAT(tShort) AS shortname 
						FROM ".$wpdb->prefix."teams t 
						JOIN ".$wpdb->prefix."user_teams ut 
						ON t.team_id = ut.team_id WHERE ut.user_id = prg.userid
						) AS teams,SUM(prg.amount)AS useramnt,
						usr.display_name,
						(
						SELECT(SUM(mm.miles_account)+SUM(mm.miles_chars)+SUM(mm.miles_review)+SUM(mm.miles_complete)+SUM(mm.miles_sharing)+SUM(mm.miles_message)+SUM(miles_locations)+SUM(miles_enrichements))  
						FROM ".$wpdb->prefix."user_miles mm 
						WHERE mm.userid=prg.userid
						) AS umiles 
						FROM ".$wpdb->prefix."user_transcriptionprogress prg 
						JOIN ".$wpdb->prefix."users usr 
						ON usr.ID=prg.userid 
						GROUP BY prg.userid 
						ORDER BY useramnt DESC";
			$topusrs = $wpdb->get_results($query,ARRAY_A);
		}
		$i = 1;
		foreach($topusrs as $usr){
			if($usr['userid'] == get_current_user_id()){
				echo "<h4>Your rank:</h4>";
				echo "<ul class=\"topusers\">\n";
				$aut = get_user_by('ID',$usr['userid']);
				um_fetch_user( $usr['userid']);
				echo "<li class=\"userRank\">";
				echo "<div class=\"tct-user-banner ".um_user('role')."\">".ucfirst(um_user('role'))."</div>\n"; 
				$acs = $wpdb->get_results("SELECT ac.*,uc.campaign_title,CASE ac.placing WHEN '1' then uc.campaign_badge_1 WHEN '2' then uc.campaign_badge_2 WHEN '3' then uc.campaign_badge_3 END AS badge FROM ".$wpdb->prefix."user_achievments ac LEFT JOIN ".$wpdb->prefix."user_campaigns uc ON uc.id=ac.campaign WHERE ac.userid='".$usr['userid']."'",ARRAY_A);
				if(sizeof($acs)>0){
					echo "<div class=\"tct_tt-achievments\">\n"; 
					foreach($acs as $ac){
						echo "<div title=\"".$ac['campaign_title']."\"class=\"".$ac['badge']."\"></div>\n";
					}
					echo "</div>\n";
				}
				if($kind === "campaign"){
					if(isset($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams']) && trim($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams']) == "1" && trim($usr['teams']) != ""){$temm = " (".str_replace(",",", ",$usr['teams']).")";}else{ $temm = "";}
					$miles = "<span class=\"milage\">".sprintf( esc_html( _n( '%s Mile', '%s Miles', (int)$usr['umiles'], 'transcribathon'  ) ), number_format_i18n((int)$usr['umiles']))."</span>\n";
					$charsc = "<span class=\"chars\"><strong>".sprintf( esc_html( _n( '%s Character in this campaign', '%s Characters in this campaign', (int)$usr['useramnt'], 'transcribathon'  ) ), number_format_i18n((int)$usr['useramnt']))."</strong></span>\n";
					$chars = "<span class=\"chars\">".sprintf( esc_html( _n( '%s Character', '%s Characters', (int)$usr['totalchars'], 'transcribathon'  ) ), number_format_i18n((int)$usr['totalchars']))."</span>\n";
					echo "<span class=\"rang\">".$i."</span><h2><a href=\"/".ICL_LANGUAGE_CODE."/user/".$aut->user_nicename."/\">".um_user('display_name')."</a><span class=\"teammem\">".$temm."</span></h2><p>".$charsc."</p><p><p>".$miles." | ".$chars."</p></p></li>\n";
				}else{
					if(isset($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams']) && trim($instance['tct-top-transcribers-settings-individuals']['tct-top-transcribers-showteams']) == "1" && trim($usr['teams']) != ""){$temm = " (".str_replace(",",", ",$usr['teams']).")";}else{ $temm = "";}
					$miles = "<span class=\"milage\">".sprintf( esc_html( _n( '%s Mile', '%s Miles', (int)$usr['umiles'], 'transcribathon'  ) ), number_format_i18n((int)$usr['umiles']))."</span>\n";
					$chars = "<span class=\"chars\">".sprintf( esc_html( _n( '%s Character', '%s Characters', (int)$usr['useramnt'], 'transcribathon'  ) ), number_format_i18n((int)$usr['useramnt']))."</span>\n";
					echo "<span class=\"rang\">".$i."</span><h2><a href=\"/".ICL_LANGUAGE_CODE."/user/".$aut->user_nicename."/\">".um_user('display_name')."</a><span class=\"teammem\">".$temm."</span></h2><p>".$miles." | ".$chars."</p></li>\n";
				}
				echo "</ul>\n";
				break;
			}
			$i++;
		}
	}

?>