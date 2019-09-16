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
                <div class="tutorial-window-popup-header">
					<h2 class="theme-color">HOW TO TRANSCRIBE</h2>
                    <span class="tutorial-window-close">&times;</span>
                </div>
                <div class="tutorial-window-popup-body">
					<div class="tutorial-left">
					<ul>
					<li>Register</li>
					<li>Enrich</li>
					<li>Step 1: Transcription</li>
					<li>Step 2: Description</li>
					<li>Step 3: Location</li>
					<li>Step 4: Tagging</li>
					<li>What are the different user-roles and how and when does my user-roles change?</li>
					<li>What are miles in this context and how do I earn them?</li>
					<li>I finished a transcription-now what?</li>
					<li>How can I ask other members for help?</li>
					</ul>
					</div>
					<div class="tutorial-right tutorial-window-slider">
						<div class="testing active slick-slide">
							<div class="tutorial-image-area">
								<?php 
									echo '<img src="'.CHILD_TEMPLATE_DIR.'/images/tutorial-trial.png">';
								?>
							</div>
							<div class="tutorial-text-area"><h2>Register</h2><br>
							<p>To contribute to Transcribathon, you must first create an account. You can 
							work on all projects using one account. Follow the instructions on the Registration 
							[LINK] page to set up your Transcribathon account.</p><br>
							<p>You can only add new enrichments to items marked with the grey 
							(Not Started) or yellow (Edit) statuses. Tasks for items in the orange 
							and green statuses are explained in the Review [LINK to ANCHOR] section of 
							this tutorial.</p>
							</div>
							<div style="clear:both;"></div>
						</div>
						<div class="testing slick-slide">
							<div><img src=""></div>
							<div class="tutorial-text-area"><h2>Enrich</h2><br>
							<p>Enrichment takes place on the item page. First, find an item (a single document) you want to enrich by searching and browsing the project collection on the Discovery Page [LINK]. Click on a story (a bundle of items) and select an item from the story page, or select an item directly from your discovery results. </p></div>
							<br><p>Get started by clicking on the pen button in the left menu of the item image. This will open up the full-screen Enrichment Mode. Edit your workspace view by using the top-right menu. You can have the white Activity Panel docked to the right (default) , to the bottom , or as an independent overlay . If you just want to view the image, you can hide the panel using the minimise button , and then re-open it again with the pencil button. Adjust the size and location of your activity panel </p>
							<div style="clear:both;"></div>
						</div>
						<div class="testing slick-slide">
							<div><img src=""></div>
							<div class="tutorial-text-area"><p>You enrich documents following a step-by-step process. Each of these steps are explained below.</p></div>
							<div><h2>Transcription</h2><br>
							<p>To start a transcription, select the transcription tab (the pencil) at the top of the activity panel. 
							Click inside the box underneath the heading TRANSCRIPTION and start writing your transcription.
							 Use the toolbar to format your text and to add special characters and tables. A guide to the
							  toolbar is available in the Formatting section.</p><br>
							  <p>Identify the language(s) of the text using the dropdown list under the transcription box. You can select multiple languages at once.</p><br>
							  <p>If the image has no text to transcribe, tick the checkbox ‘No Text’.</p><br>
							  <p>Once you have finished, click SAVE.</p>
							</div>
							<div style="clear:both;"></div>
						</div>
						<div class="testing slick-slide">
							<div><img src=""></div>
							<div class="tutorial-text-area"><h2>Description</h2><br>
							<p>You can add a description underneath the Transcription field. The first task is to identify what type of document the item is: a letter, diary, postcard or picture. Users should tick the category which best applies to the item. Multiple categories can be selected at once.</p><br>
							  <p>The second task is to write the description. Click inside the box underneath the heading DESCRIPTION. Here, you can write what the item is, what it is about, and specify the images and objects that appear in the item.</p><br>
							  <p>Identify the language of the description text that you wrote using the dropdown list underneath. You can only select one language.</p><br>
							  <p>Once you have finished your description, click SAVE.</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="testing slick-slide">
							<div><img src=""></div>
							<div class="tutorial-text-area"><h2>Location</h2><br>
							<p>To tag locations to the item, select the tagging tab (map pin and tag icons) at the top of the activity panel. Click ADD LOCATION.</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="testing slick-slide">
							<div><img src=""></div>
							<div class="tutorial-text-area"><h2>Tagging</h2><br>
							<p></p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="testing slick-slide">
							<div><img src=""></div>
							<div class="tutorial-text-area"><h2></h2><br>
							<p></p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="testing slick-slide">
							<div><img src=""></div>
							<div class="tutorial-text-area"><h2></h2><br>
							<p></p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="testing slick-slide">
							<div><img src=""></div>
							<div class="tutorial-text-area"><h2>Formatting</h2><br>
							<p>Guidelines on how users should format their transcription using special tools. Toolbar diagram with explanations for each section.</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="testing slick-slide">
							<div><img src=""></div>
							<div class="tutorial-text-area"><h2>Review</h2><br>
							<p>How to review and complete contributions to each of the four steps, and how they are locked to advanced users.</p></div>
							<div style="clear:both;"></div>
						</div>
						<div class="testing slick-slide">
							<div class="tutorial-image-area"><img src='https://transcribathon.com/wp-content/uploads/PB281692c-2-436x272.jpg' alt=''/></div>
							<div class="tutorial-text-area"><p>10 tutorial text in the popup..tutorialtutorial text in the popup.. text in the popup..tutorial text in the popup..tutorial text in the popup..tutorial text in the popup..</p></div>
							<div style="clear:both;"></div>
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
                                jQuery('#tutorial-mode').click(function() {
								jQuery('#tutorial-popup-window-container').css('display', 'block');
								jQuery('.tutorial-window-slider').slick('refresh');
								
                                })
                                
                                // When the user clicks on <span> (x), close the modal
                                jQuery('.tutorial-window-close').click(function() {
                                jQuery('#tutorial-popup-window-container').css('display', 'none');
								})			
								var modal = document.getElementById('tutorial-popup-window-container');
								window.onclick = function(event) {
										if (event.target == modal) {
										modal.style.display = "none";
										}
									}					
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

		

				