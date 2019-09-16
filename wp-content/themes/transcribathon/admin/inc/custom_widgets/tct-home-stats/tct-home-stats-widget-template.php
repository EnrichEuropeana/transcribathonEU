<?php
global $wpdb;
$myid = uniqid(rand()).date('YmdHis');
$base = 0;



if(isset($instance['tct-home-stats-headline']) && trim($instance['tct-home-stats-headline']) != ""){ echo "<h1 style=\"text-align: center\">".str_replace("\n","<br />",$instance['tct-home-stats-headline'])."</h1>\n"; }


// Total characters
$url = home_url()."/tp-api/statistics/characters";
$requestType = "GET";
include TCT_THEME_DIR_PATH."admin/inc/custom_scripts/send_api_request.php";
$characters = json_decode($result, true);

// Total enrichments
$url = home_url()."/tp-api/statistics/enrichments";
$requestType = "GET";
include TCT_THEME_DIR_PATH."admin/inc/custom_scripts/send_api_request.php";
$enrichments = json_decode($result, true);

// Total items
$url = home_url()."/tp-api/statistics/items";
$requestType = "GET";
include TCT_THEME_DIR_PATH."admin/inc/custom_scripts/send_api_request.php";
$items = json_decode($result, true);

echo "<div style=\"text-align: center\" class=\"home-stats-content section group\">\n";
	echo "<div class=\"column-rgs span_1_of_3\">\n";
		echo "<div class=\"stat-number\">\n";
			echo "<h3 class=\"theme-color\">".$items." <div class=\"decoration\"><div class=\"decoration-inside theme-color-background\"></div></div> <span>Documents</span></h3>\n";
		echo "</div>\n";
	echo "</div>\n";
	echo "<div class=\"column-rgs span_1_of_3\">\n";
		echo "<div class=\"stat-number\">\n";
			echo "<h3 class=\"theme-color\">".$characters." <div class=\"decoration\"><div class=\"decoration-inside theme-color-background\"></div></div> <span>Characters</span></h3>\n";
		echo "</div>\n";
	echo "</div>\n";
	echo "<div class=\"column-rgs span_1_of_3\">\n";
		echo "<div class=\"stat-number\">\n";
			echo "<h3 class=\"theme-color\">".$enrichments."  <div class=\"decoration\"><div class=\"decoration-inside theme-color-background\"></div></div> <span>Enrichments</span></h3>\n";
		echo "</div>\n";
	echo "</div>\n";
echo "</div>\n";

?>