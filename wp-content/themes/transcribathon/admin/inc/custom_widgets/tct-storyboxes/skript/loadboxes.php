<?php include(str_repeat("../",(sizeof(explode("/",substr((string)getcwd(),strrpos((string)getcwd(),"/wp-content"),strlen((string)getcwd()))))-2))."../wp-load.php");
global $wpdb,$mysql_con;


//{'q':'gtttrs','myid':myid,'base':base,'limit':limit}, function(res) 

if(isset($_POST['q']) && $_POST['q'] === "gmbxs"):
	$content = "";
	$tct_doccols = (int)$_POST['cols'];
	$toshow = explode("|",trim($_POST['ids'])); 
	$my_args = array('post_type'=>'documents','numberposts'=>12,'paged'=>true,'order_by'=>'post_date','order'=>'DESC',
		'post__in' => $toshow,
	);
	$str = get_posts($my_args);
	
	$content .= "<div class=\"section group sepgroup tab\">\n";
	global $post;
	$i=0;
	foreach($str as $post){
		if($i<$tct_doccols){ $i++; }else{ $i=1; $content .= "</div>\n<div class=\"section group tab sepgroup\">\n"; }
		
		ob_start();
		include(locate_template('document.php'));
		$content .= ob_get_clean();
		
		
		//get_template_part(document);
		if($tct_doccols === 4){
			if($i==2){ $content .=  "<span class=\"sep\"></span>\n";}
		}else if($tct_doccols === 3){
			if($i==2){ $content .=  "<span class=\"sep\"></span>\n";}
		}
	}
	wp_reset_postdata();
	$content .= "</div>\n";	
	

	//Rueckgabe 
	$res = array();
	$res['status'] = 'ok';
	$res['boxes'] = $content;
	header("Content-Type: text/json; charset=utf-8");
	echo trim(json_encode($res));
endif;	

?>