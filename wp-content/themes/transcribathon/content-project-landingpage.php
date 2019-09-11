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
			<a class="bubble help theme-color-background" id="tutorial-mode" href="#"><i class="fal fa-question-circle"></i><br />&nbsp;<br />How to <br />start</a>
		</div>
		<div id="tutorial-popup-window-container">
            <div id="tutorial-window-popup">
                <div class="tutorial-window-popup-header theme-color-background">
                    <span class="tutorial-window-close">&times;</span>
                </div>
                <div class="tutorial-window-popup-body tutorial-window-slider">
						<div class="slick-slide">
							<div class="tutorial-image-area"><img data-lazy='https://transcribathon.com/wp-content/uploads/PB281692c-2-436x272.jpg' alt=''/></div>
							<div class="tutorial-text-area"><p>1 tutorial text in the popup..tutorialtutorial text in the popup.. text in the popup..tutorial text in the popup..tutorial text in the popup..tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="slick-slide">
							<div><img src=""></div>
							<div><p>2 tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="slick-slide">
							<div><img src=""></div>
							<div><p>3 tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="slick-slide">
							<div><img src=""></div>
							<div><p>4 tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="slick-slide">
							<div><img src=""></div>
							<div><p>5 tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="slick-slide">
							<div><img src=""></div>
							<div><p>6 tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="slick-slide">
							<div><img src=""></div>
							<div><p>7 tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="slick-slide">
							<div><img src=""></div>
							<div><p>8 tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="slick-slide">
							<div><img src=""></div>
							<div><p>9 tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="slick-slide">
							<div class="tutorial-image-area"><img data-lazy='https://transcribathon.com/wp-content/uploads/PB281692c-2-436x272.jpg' alt=''/></div>
							<div class="tutorial-text-area"><p>10 tutorial text in the popup..tutorialtutorial text in the popup.. text in the popup..tutorial text in the popup..tutorial text in the popup..tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
						</div>
				</div>
                <!--<div class="tutorial-window-popup-footer theme-color-background"></div>-->
			
        	</div>
        </div>
		<!--<div id="myModal" class="modal">

			<div class="modal-content">
				<span class="close">&times;</span>
				<p>tutorial text in the popup..</p>
			</div>

		</div>-->
		<script>
		jQuery ( document ).ready(function() {
                                // When the user clicks the button, open the modal 
                                jQuery('#tutorial-mode').click(function() {
								jQuery('#tutorial-popup-window-container').css('display', 'block');
								jQuery('.tutorial-window-slider').slick('refresh');
								
                                })
                                
                                // When the user clicks on <span> (x), close the modal
                                jQuery('.tutorial-window-close').click(function() {
                                jQuery('#tutorial-popup-window-container').css('display', 'none');
                                })
                            });
		</script>
		<script>
                        jQuery(document).ready(function(){
                            jQuery('.tutorial-window-slider').slick({
                                slidesToShow: 1,
								slidesToScroll: 1,
								dots: true,
								infinite: false,
								lazyLoad: 'ondemand'

                            });
                        });
                </script>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'vantage' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->

		<?php do_action('vantage_entry_main_bottom') ?>

	</div>

</article><!-- #post-<?php the_ID(); ?> -->

		

				