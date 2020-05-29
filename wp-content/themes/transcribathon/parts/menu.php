<?php
/**
 * Part Name: Default Menu
 */

$ubermenu_active = function_exists( 'ubermenu' );
$max_mega_menu_active = function_exists( 'max_mega_menu_is_enabled' ) && max_mega_menu_is_enabled( 'primary' );
$nav_classes = array( 'site-navigation' );
if ( ! $ubermenu_active && ! $max_mega_menu_active ) {
	$nav_classes[] = 'main-navigation';
}
$nav_classes[] = 'primary';

if ( siteorigin_setting( 'navigation_use_sticky_menu' ) ) {
	$nav_classes[] = 'use-sticky-menu';
}

if ( siteorigin_setting( 'navigation_mobile_navigation' ) ) {
	$nav_classes[] = 'mobile-navigation';
}
$logo_in_menu = siteorigin_setting( 'layout_masthead' ) == 'logo-in-menu';
?>
<nav role="navigation" class="<?php echo implode( ' ', $nav_classes) ?>">
    <div class="_transcribathon_mainnav">
    <?php 
        // Allways home of transcribathon
		$theme_sets = get_theme_mods();
		
		echo "<a href=\"".network_home_url()."\" class=\"_transcribathon_logo\"></a>";
		if(!is_main_site()) {
			if(!is_home()){
				echo "<a href=\"".get_home_url()."\" class=\"_transcribathon_partnerlogo\" id=\"_transcribathon_partnerlogo\" >"; vantage_display_logo(); echo "</a>";
			}else{
				echo "<span class=\"_transcribathon_partnerlogo\" id=\"_transcribathon_partnerlogo\">"; vantage_display_logo(); echo "</span>";
			}
		}
		
    
	echo "\n<ul id=\"_transcribathon_topmenu\" class=\"menu\">\n";
		echo "<li><a href=\"".network_home_url()."/contact/\" class=\"contact-area\">Contact Us</a></li>";
		echo "<li id=\"projects\" class=\"menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children projects\">Projects\n";
			$sites = get_sites(array('site__not_in'=>array('1'),'deleted'=>0));
			echo "<ul class=\"sub-menu\" style=\"display: none; opacity: 0;\">\n";
				$i=1;
				foreach($sites as $s){
					echo "<li id=\"projects-".$i."\" class=\"menu-item menu-item-type-post_type menu-item-object-page projects-".$i." top_nav_point-".$s->blog_id."\"><a href=\"https://".$s->domain.$s->path."\">".get_blog_details($s->blog_id)->blogname."</a></li>\n";
					$i++;
				}
			echo "</ul>\n";
		echo "</li>\n";
		echo "<li id=\"projects\" class=\"menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children projects\">\n";
		echo "</li>\n";
		if (is_user_logged_in()){
			echo "<li id=\"account-menu\" class=\"menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children\">".wp_get_current_user()->user_login."\n";
				echo "<ul class=\"sub-menu\" style=\"display: none; opacity: 0;\">\n";
					echo "<li id=\"account\" class=\"menu-item menu-item-type-post_type menu-item-object-page account-menu-item\"><a href=\"".network_home_url()."/account\">Account</a></li>\n";
					echo "<li id=\"profile\" class=\"menu-item menu-item-type-post_type menu-item-object-page account-menu-item\"><a href=\"".network_home_url()."/profile\">Profile</a></li>\n";
					echo "<li id=\"logout\" class=\"menu-item menu-item-type-post_type menu-item-object-page account-menu-item\"><a href=\"".network_home_url()."/logout\">Logout</a></li>\n";
				echo "</ul>\n";
			echo "</li>\n";
			}
		else {
			echo "<li id=\"register\" class=\"menu-item menu-item-type-post_type menu-item-object-page\">\n";
				echo "<a href=\"".network_home_url()."/register/ \">Register</a>";
			echo "</li>\n";
			echo "<li id=\"default-lock-login\" class=\"menu-item menu-item-type-post_type menu-item-object-page\">\n";
				echo "<a id=\"login\" href=\"#\">Login</a>";
			echo "</li>\n";
		}

		
	// Login modal
	echo '<div id="default-login-container">';
		echo '<div id="default-login-popup">';
			echo '<div class="default-login-popup-header theme-color-background">';
				echo '<span class="item-login-close">&times;</span>';
			echo '</div>';
			echo '<div class="default-login-popup-body">';
				$login_post = get_posts( array(
					'name'    => 'default-login',
					'post_type'    => 'um_form',
				));
				echo do_shortcode('[ultimatemember form_id="'.$login_post[0]->ID.'"]');
			echo '</div>';
			echo '<div class="default-login-popup-footer theme-color-background">';
			echo '</div>';
		echo '</div>';
	echo '</div>';
		
	echo "</ul>\n";
	echo '<div id="help-tutorials" class="help-tutorials">';
	echo '<a href="#" class="tutorial-model" title="Tutorial"><i class="fal fa-question-circle"></i></a>';
	echo '</div>';

	echo "<script> 
 	jQuery('#default-lock-login').click(function() {
	  jQuery('#default-login-container').css('display', 'block');
	})
  
 	jQuery('.item-login-close').click(function() {
	  jQuery('#default-login-container').css('display', 'none');
	})
	jQuery ( document ).ready(function() {
							// When the user clicks the button, open the modal 
							jQuery('#help-tutorials').click(function() {
							jQuery('#tutorial-popup-window-container').css('display', 'block');
							jQuery('#tutorial-menu-slider-area').slick('refresh');
							
							})
							
							// When the user clicks on <span> (x), close the modal
							jQuery('.tutorial-window-close').click(function() {
							jQuery('#tutorial-popup-window-container').css('display', 'none');
							})		
							
							jQuery('#tutorial-popup-window-container').mousedown(function(event){
								if (event.target.id == 'tutorial-popup-window-container') {
									jQuery('#tutorial-popup-window-container').css('display', 'none')
								}
							})			
						});
						
	 </script>"; 


// Help tab 
     ?>
    </div>

	<!-- <div class="full-container">-->
		<?php if($logo_in_menu) : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="logo"><?php vantage_display_logo(); ?></a>
		<?php endif; ?>
		<?php if( siteorigin_setting('navigation_menu_search') && ! $max_mega_menu_active ) : ?>
			<div id="search-icon">
				<div id="search-icon-icon"><?php echo vantage_display_icon( 'search' ); ?></div>
				<?php get_search_form() ?>
			</div>
		<?php endif; ?>

		<?php if( $ubermenu_active ): ?>
			<?php ubermenu( 'main' , array( 'theme_location' => 'primary' ) ); ?>
		<?php else: ?>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'link_before' => '<span class="icon"></span>' ) ); ?>
		<?php endif; ?>
	<!--</div>-->
</nav><!-- .site-navigation .main-navigation -->
