<?php
// get Document data from API
function _TCT_get_document_data( $atts ) {   
    $content = "";
    if (isset($_GET['story']) && $_GET['story'] != "") {
        // get Story Id from url parameter
        $storyId = $_GET['story'];

        // Set request parameters
        $requestData = array(
            'key' => 'testKey'
        );
        $url = network_home_url()."/tp-api/Story/".$storyId;
        $requestType = "POST";
    
        // Execude request
        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

        // Display data
        $storyData = json_decode($result, true);
        $storyData = $storyData[0];

        // Top image slider 
        $content .= "<div class='story-page-slider'>";
            foreach ($storyData['Items'] as $item) {
                $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."'><img data-lazy='".$item['ImageLink']."'></a>";
            }
            $content .= "<div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258363.full-150x150.jpg'></div>
                            <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258364.full-150x150.jpg'></div>
                            <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258365.full-150x150.jpg'></div>
                            <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258366.full-150x150.jpg'></div>
                            <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258367.full-150x150.jpg'></div>
                            <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258368.full-150x150.jpg'></div>
                            <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258369.full-150x150.jpg'></div>
                            <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258370.full-150x150.jpg'></div>
                        <div><img data-lazy='https://transcribathon.com/wp-content/uploads/document-images/21795.258371.full-150x150.jpg'></div>";
        $content .= "</div>";

        // Image slider JavaScript
        $content .= "<script>
            jQuery(document).ready(function(){
                jQuery('.story-page-slider').slick({
                    dots: true,
                    arrows: false,
                    speed: 300,
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    lazyLoad: 'ondemand',
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                            }
                        },
                        {
                            breakpoint: 800,
                            settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                            }
                        }
                    ]
                })
            });
        </script>";








foreach ($storyData['Items'] as $item){
    // create links to items
    $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."' class='story-page-item-link'>".$item['Title']."</a></br>";
}


        /* POPUP modal code for items. Not in use for now
        // build page content
        foreach ($data['Items'] as $item){
            // create button to open item
            $content .= "<button id=".$item['ItemId']." type='button' class='btn btn-success openBtn'>".$item['Title']."</button>";
        }

            // create modal window structure
            $content .= '
                <!-- Modal -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog item-modal">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-body">';

                            // Item content will be loaded into this section

            $content .=     '</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>';
            
            // get Item content from getItemContent.php
            $content .= '
                <script>
                    jQuery(".openBtn").on("click",function(){
                        jQuery(".modal-body").load("/../wp-content/themes/transcribathon/getItemContent.php?id=" + jQuery(this).attr("id") ,function(){
                            jQuery("#myModal").modal({show:true});
                        });
                    });
                </script>';

        // opem modal window on page load if direct item link is used
        if (isset($_GET['item']) && $_GET['item'] != "") {
            $content .= '
            <script>
                jQuery(document).ready(function(){
                    jQuery(".modal-body").load("/../wp-content/themes/transcribathon/getItemContent.php?id=" + '.$_GET['item'].' ,function(){
                        jQuery("#myModal").modal({show:true});
                    });
                });
            </script>';
        }
        */
    }
    return $content;
}
add_shortcode( 'get_document_data', '_TCT_get_document_data' );
?>