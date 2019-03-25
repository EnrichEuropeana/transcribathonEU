<?php session_id() or session_start(); include(str_repeat("../",(sizeof(explode("/",substr((string)getcwd(),strrpos((string)getcwd(),"/wp-content"),strlen((string)getcwd()))))-2))."../wp-load.php"); header("content-type: text/css");
$theme_sets = get_theme_mods();
// Colored-Bottom-Border on Project-Logo
echo "span._transcribathon_partnerlogo,a._transcribathon_partnerlogo{border-bottom:5px solid ".$theme_sets['vantage_general_link_color'].";}\n";
// Navigation-Color
echo "nav[role=navigation] ul#menu-main-menu li a{color:".$theme_sets['vantage_general_link_color'].";}\n";
echo "nav[role=navigation] ul#menu-main-menu li a:hover{background-color:".$theme_sets['vantage_general_link_hover_color']."; color:#fff;}\n";
echo "ul#_transcribathon_topmenu li ul li a:hover{background-color:".$theme_sets['vantage_general_link_hover_color']." ;}\n";
echo "h1{color:".$theme_sets['vantage_general_link_color']." !important;}\n";
?>