<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package vantage
 * @since vantage 1.0
 * @license GPL 2.0
 */
?>
    
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-main">
		<!-- page header, including meta-slider -->
		<?php do_action('vantage_entry_main_top') ?>

		<!-- bubbles -->
		<div class="entry-content bubbles">  
			<a class="bubble search theme-color-background" href="documents"><i class="fal fa-search"></i><br />&nbsp;<br />Search <br /></a>
			<a class="bubble big transcribe-now theme-color-background" href="documents"><i class="fal fa-pen"></i><br />&nbsp;<br />Transcribe <br />now</a>
			<a class="bubble help theme-color-background tutorial-model" id="tutorial-mode"><i class="fal fa-question-circle"></i><br />&nbsp;<br />How to <br />start</a>
		</div>
		<div id="tutorial-popup-window-container">
            <div id="tutorial-window-popup">
                <div class="tutorial-window-popup-header">
					<h2 class="theme-color">HOW TO TRANSCRIBE</h2>
                    <span class="tutorial-window-close">&times;</span>
                </div>
                <div class="tutorial-window-popup-body">
					<div class="tutorial-left action">
					<ul>
						<li><a href="#" data-slide="1" class="theme-color"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Register</span></a></li>
						<li><a href="#" data-slide="2"><i class="fal fa-long-arrow-right"  style="margin-right: 5px;"></i><span>Enrich</span></a>
							<ul>
								<li><a href="#" data-slide="5"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Step 1: Transcription</span></a></li>
								<li><a href="#" data-slide="6"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Step 2: Description</span></a></li>
								<li><a href="#" data-slide="7"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Step 3: Location</span></a></li>
								<li><a href="#" data-slide="8"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Step 4: Tagging</span></a></li>
								<li><a href="#" data-slide="9"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Step 5: Mark for Review</span></a></li>
							</ul>
						</li>
						<li><a href="#" data-slide="10"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Formatting</span></a></li>
						<li><a href="#" data-slide="11"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Review</span></a></li>
						<li><a href="#" data-slide="12"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Completion Statuses</span></a></li>
						<li><a href="#" data-slide="13"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Miles and Levels</span></a></li>
					</ul>
					</div>
					
					<?php
						//include theme directory for text hovering
						$theme_sets = get_theme_mods();
						echo "<style>
						.tutorial-window-slider button.slick-arrow{
							color: ".$theme_sets['vantage_general_link_hover_color']." !important;
						}
				</style>";
										// Get all tutorial posts
						$args = array( 
							'posts_per_page'   => 50,
							'post_type'		=> 'tutorial', // or 'post', 'page'
							'meta_key' => 'tct_tutorial_order',
							'orderby'  => 'meta_value_num',
							'order'			=> 'ASC'
							);

						$tutorialPosts = get_posts($args);
						global $_wp_additional_image_sizes;
					?>
					<div class="tutorial-right tutorial-window-slider">
						<?php
							foreach ($tutorialPosts as $tutorialPost) {
								echo "<div class='testing active slick-slide'>";
									if (get_post_meta($tutorialPost->ID, "_thumbnail_id")[0] != null) {
										echo "<div class='tutorial-image-area'>";
											echo '<img data-lazy="'.wp_get_attachment_image_src(get_post_meta($tutorialPost->ID, "_thumbnail_id")[0], 
															array($_wp_additional_image_sizes['tutorial-image']['width'],$_wp_additional_image_sizes['tutorial-image']['height']))[0].'" alt=""/>';
										echo "</div>";
										echo "<div class='tutorial-text-area'>";
											echo "<h2 class='theme-color tutorial-headline'>".$tutorialPost->post_title."</h2>";
											echo $tutorialPost->post_content;
										echo "</div>";
									}
									else {
										echo "<div class='tutorial-text-area' style='height: 100%'>";
											echo "<h2 class='theme-color tutorial-headline'>".$tutorialPost->post_title."</h2>";
											echo $tutorialPost->post_content;
										echo "</div>";
									}
									echo '<div style="clear:both;"></div>';
								echo "</div>";
							}
						?>
						<!--<div class="testing active slick-slide">
							<div class="tutorial-image-area">
									echo '<img src="'.CHILD_TEMPLATE_DIR.'/images/tutorial-trial.png">';
								?>
							</div> -->
 						</div> 
					</div>
				</div>
				<!--<div class="prev">Prev</div>

				<div class="dots"></div>
				<div class="next">Next</div>
                <div class="tutorial-window-popup-footer theme-color-background"></div>-->
			
        	</div>
        </div>
		
		<script>

                        jQuery(document).ready(function(){
                            jQuery('.tutorial-window-slider').slick({
                                slidesToShow: 1,
								slidesToScroll: 1,
								dots: false,
								infinite: false,
								lazyLoad: 'ondemand'
							});
							jQuery('a[data-slide]').click(function(e) {
							e.preventDefault();
							var slideno = jQuery(this).data('slide');
								jQuery('a[data-slide]').removeClass("theme-color");
								jQuery(this).addClass("theme-color");
							jQuery('.tutorial-window-slider').slick('slickGoTo', slideno - 1);
							});
                        });
		</script>
		<!--<div id="myModal" class="modal">

			<div class="modal-content">
				<span class="close">&times;</span>
				<p>tutorial text in the popup..</p>
			</div>

		</div>-->
		<script>
		jQuery ( document ).ready(function() {
                                // When the user clicks the button, open the modal 
                                jQuery('.tutorial-model').click(function() {
								jQuery('#tutorial-popup-window-container').css('display', 'block');
								jQuery('.tutorial-window-slider').slick('refresh');
								
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
							
		</script> 
			<!--<script>
				jQuery(function(){
					len = jQuery('.testing').length;
					jQuery(document).on('click','span',function(){
						current = jQuery(this).index();
						if(current == len){
							return false;
						}
						jQuery('.testing.active').removeClass('active').hide();
						jQuery('.testing:eq('+current+')').addClass('active').show();
					});
					jQuery('.prev').click(function() {       
						var activeIndex = (jQuery('.testing.active').index() - 1);
						jQuery('.testing.active').removeClass('active').hide();
						jQuery('.testing:eq(' + activeIndex + ')').addClass('active').show();
					})
					jQuery('.next').click(function() {       
						var activeIndex = ($('.testing.active').index() + 1);
						jQuery('.testing.active').removeClass('active').hide();
						jQuery('.testing:eq(' + activeIndex + ')').addClass('active').show();
					})

					for(i = 1;i <= len;i++){
						jQuery('.dots').append('<span>'+i+'</span>');
					}
					jQuery('').click(function(){
					if(jQuery('.testing.active').index() + 1 ==len){
						return false;
					}	jQuery('.testing.active').removeClass('active').hide().next().addClass('active').show();
					})
				});
			</script>-->
		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'vantage' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->

		<?php do_action('vantage_entry_main_bottom') ?>

	</div>

</article><!-- #post-<?php the_ID(); ?> -->

		

				