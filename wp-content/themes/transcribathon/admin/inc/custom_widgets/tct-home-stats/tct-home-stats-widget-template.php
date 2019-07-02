<?php
global $wpdb;
$myid = uniqid(rand()).date('YmdHis');
$base = 0;



if(isset($instance['tct-home-stats-headline']) && trim($instance['tct-home-stats-headline']) != ""){ echo "<h1 style=\"text-align: center\">".str_replace("\n","<br />",$instance['tct-home-stats-headline'])."</h1>\n"; }

echo "<div style=\"text-align: center\" class=\"home-stats-content section group\">\n";
	echo "<div class=\"column-rgs span_1_of_3\">\n";
		echo "<div class=\"stat-number\">\n";
			echo "<h3 class=\"theme-color\">23.102 <div class=\"decoration\"><div class=\"decoration-inside theme-color-background\"></div></div> <span>Documents</span></h3>\n";
		echo "</div>\n";
	echo "</div>\n";
	echo "<div class=\"column-rgs span_1_of_3\">\n";
		echo "<div class=\"stat-number\">\n";
			echo "<h3 class=\"theme-color\">12.768.001 <div class=\"decoration\"><div class=\"decoration-inside theme-color-background\"></div></div> <span>Characters</span></h3>\n";
		echo "</div>\n";
	echo "</div>\n";
	echo "<div class=\"column-rgs span_1_of_3\">\n";
		echo "<div class=\"stat-number\">\n";
			echo "<h3 class=\"theme-color\">78.326 <div class=\"decoration\"><div class=\"decoration-inside theme-color-background\"></div></div> <span>Enrichments</span></h3>\n";
		echo "</div>\n";
	echo "</div>\n";
echo "</div>\n";

?>