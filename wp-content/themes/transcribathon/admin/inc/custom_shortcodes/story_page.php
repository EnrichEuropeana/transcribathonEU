<?php
// get Document data from API
function _TCT_get_document_data( $atts ) {   
    $content = "";
    if (isset($_GET['id']) && $_GET['id'] != "") {
        // get Story Id from url parameter
        $storyId = $_GET['id'];

        // get Story data from API
        $json = file_get_contents(network_home_url()."/tp-api/Story/".$storyId);
        $data = json_decode($json, true);
        $data = $data[0];

        foreach ($data['Items'] as $item){
            // create links to items
            $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?id=".$item['ItemId']."' class='story-page-item-link'>".$item['Title']."</a></br>";
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