<?php
/*
Shortcode: tutorial_slider
Description: Gets tutorial slides and builds
*/

// include required files
include($_SERVER["DOCUMENT_ROOT"].'/wp-load.php');


function _TCT_tutorial_slider( $atts ) {
    global $ultimatemember;
    if (isset($_GET['item']) && $_GET['item'] != "") {

  
        //include theme directory for text hovering
        $theme_sets = get_theme_mods();
        
        // Build tutorial slider content
        $content = "";
        $content = "<style>
                         
                    </style>";

                   // $content .= do_shortcode('[tutorial_slider]');
                   /*
        $content .= '<div id="tutorial-popup-window-container">';
            $content .= '<div id="tutorial-window-popup">';
                $content .= '<div class="tutorial-window-popup-header">';
                    $content .= '<h2 class="theme-color">HOW TO TRANSCRIBE</h2>';
                    $content .= '<span class="tutorial-window-close">&times;</span>';
                $content .= '</div>';
                $content .= '<div class="tutorial-window-popup-body">';
                    $content .= '<div class="tutorial-left action">';
                        $content .= '<ul>';
                            $content .= '<li><a href="#" data-slide="1"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Register</span></a></li>';
                            $content .= '<li><a href="#" data-slide="2"><i class="fal fa-long-arrow-right"  style="margin-right: 5px;"></i><span>Enrich</span></a>';
                                $content .= '<ul>';
                                $content .= '<li><a href="#" data-slide="5"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Step 1: Transcription</span></a></li>';
                                $content .= '<li><a href="#" data-slide="6"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Step 2: Description</span></a></li>';
                                $content .= '<li><a href="#" data-slide="7"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Step 3: Location</span></a></li>';
                                $content .= '<li><a href="#" data-slide="8"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Step 4: Tagging</span></a></li>';
                                $content .= '<li><a href="#" data-slide="9"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Step 5: Mark for Review</span></a></li>';
                                $content .= '</ul>';
                            $content .= '</li>';
                            $content .= '<li><a href="#" data-slide="10"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Formatting</span></a></li>';
                            $content .= '<li><a href="#" data-slide="11"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Review</span></a></li>';
                            $content .= '<li><a href="#" data-slide="12"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Completion Statuses</span></a></li>';
                            $content .= '<li><a href="#" data-slide="13"><i class="fal fa-long-arrow-right" style="margin-right: 5px;"></i><span>Miles and Levels</span></a></li>';
                        $content .= '</ul>';
                    $content .=  '</div>';
                           
                           */
                           
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
                    $content .= '<div class="tutorial-right tutorial-window-slider">';
                        foreach ($tutorialPosts as $tutorialPost) {
                            $content .= "<div class='testing active slick-slide'>";
                                if (get_post_meta($tutorialPost->ID, "_thumbnail_id")[0] != null) {
                                    $content .= "<div class='tutorial-image-area'>";
                                    $content .= '<img data-lazy="'.wp_get_attachment_image_src(get_post_meta($tutorialPost->ID, "_thumbnail_id")[0], 
                                                        array($_wp_additional_image_sizes['tutorial-image']['width'],$_wp_additional_image_sizes['tutorial-image']['height']))[0].'" alt=""/>';
                                                        $content .= "</div>";
                                                        $content .= "<div class='tutorial-text-area'>";
                                                        $content .= "<h2 class='theme-color tutorial-headline'>".$tutorialPost->post_title."</h2>";
                                                        $content .= $tutorialPost->post_content;
                                                        $content .= "</div>";
                                }
                                else {
                                    $content .= "<div class='tutorial-text-area' style='height: 100%'>";
                                    $content .= "<h2 class='theme-color tutorial-headline'>".$tutorialPost->post_title."</h2>";
                                    $content .= $tutorialPost->post_content;
                                    $content .= "</div>";
                                }
                                $content .= '<div style="clear:both;"></div>';
                                $content .= "</div>";
                        }
                    
                    
                        $content .= '</div>'; 
                    $content .= '</div>';
                    /*
                $content .= '</div>';
                       
                   
        $content.= '</div>';
    $content .= '</div>';
    */
    

        //  JavaScript
        $content .= "<script> 
                    jQuery(document).ready(function(){
                        jQuery('.tutorial-window-slider').slick({
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            dots: true,
                            infinite: false,
                            lazyLoad: 'ondemand'
                        });
                        jQuery('a[data-slide]').click(function(e) {
                        e.preventDefault();
                        var slideno = jQuery(this).data('slide');
                            jQuery('a[data-slide]').removeClass('theme-color-active');
                            jQuery(this).addClass('theme-color-active');
                        jQuery('.tutorial-window-slider').slick('slickGoTo', slideno - 1);
                        });
                    });
         </script>";
        $content .= "<script> 
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
							
		 </script>"; 

        return $content;
    }
}
add_shortcode( 'tutorial_slider', '_TCT_tutorial_slider' );
?>
