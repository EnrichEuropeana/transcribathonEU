<?php
// get Document data from API
function _TCT_get_document_data( $atts ) {   
    //include theme directory for text hovering
    $theme_sets = get_theme_mods();

    // Build Story page content
    $content = "";
    $content = "<style>
                  
                .story-page-slider button.slick-prev.slick-arrow:hover {
                    background: ".$theme_sets['vantage_general_link_color']." !important;
                    color: #ffffff;
                }
                
                .story-page-slider button.slick-next.slick-arrow:hover {
                    background: ".$theme_sets['vantage_general_link_color']." !important;
                    color: #ffffff;
                }

            </style>";
    if (isset($_GET['story']) && $_GET['story'] != "") {
        // get Story Id from url parameter
        $storyId = $_GET['story'];

        // Set request parameters
        $url = network_home_url()."/tp-api/stories/".$storyId;
        $requestType = "GET";
    
        // Execude request
        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

        // Display data
        $storyData = json_decode($result, true);
        $storyData = $storyData[0];

        // Top image slider 
        $content .= "<div class='story-page-slider'>";
            foreach ($storyData['Items'] as $item) {
                
                $image = json_decode($item['ImageLink'], true);
                $imageLink = $image['service']['@id'];
                if ($image["width"] <= $image["height"]) {
                    $imageLink .= "/0,0,".$image["width"].",".$image["width"];
                }
                else {
                    $imageLink .= "/0,0,".$image["height"].",".$image["height"];
                }
                $imageLink .= "/250,250/0/default.jpg";
                $content .= "<a href='https://europeana.fresenia.man.poznan.pl/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."'><img data-lazy='".$imageLink."'></a>";
                //$content .= '<div class="label shad"></div>';
                //$content .= '<div class="label complete"></div>';
            }
                
        $content .= "</div>";

        // Image slider JavaScript
        $infinite = "true";
        
        if (sizeof($storyData['Items']) > 100) {
            $infinite = "false";
        }

        $content .= "<script>
            jQuery(document).ready(function(){
                jQuery('.story-page-slider').slick({
                    dots: true,
                    infinite: ".$infinite.",
                    arrows: true,
                    speed: 300,
                    slidesToShow: 8,
                    slidesToScroll: 8,
                    lazyLoad: 'ondemand',
                    responsive: [
                        {
                            breakpoint: 1650,
                            settings: {
                            slidesToShow: 7,
                            slidesToScroll: 7
                            }
                        },
                        {
                            breakpoint: 1400,
                            settings: {
                            slidesToShow: 6,
                            slidesToScroll: 6
                            }
                        },
                        {
                            breakpoint: 1150,
                            settings: {
                            slidesToShow: 5,
                            slidesToScroll: 5
                            }
                        },
                        {
                            breakpoint: 900,
                            settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4
                            }
                        },
                        {
                            breakpoint: 650,
                            settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                            }
                        },
                        {
                            breakpoint: 400,
                            settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                            }
                        }
                    ]
                })
            });
        </script>";

        $content .= '<div class="story-navigation-area">';
            $content .= '<ul class="story-navigation-content-container left" style="">';
                $content .= '<li><a href="/documents/" style="text-decoration: none;">Stories</a></li>';
                $content .= '<li><i class="fal fa-angle-right"></i></li>';
                $content .= '<li>';
                $content .= $storyData['dcTitle'];
                $content .= '</li>';
            $content .= '</ul>';
                $content .= '<ul class="story-navigation-content-container right" style="">
                            <li class="rgt"><a title="next" href=""><i class="fal fa-angle-right" style="font-size: 20px;"></i></a></li>
                        </ul>';
        $content .= '</div>';

$content .= "<div id='total-storypg' class='storypg-container'>";
    $content .= "<div class='main-storypg'>";
                $content .= "<div class='storypg-info'>";
                    $content .= "<h1 class='storypg-title'>";
                        $content .= $storyData['dcTitle'];
                    $content .= "</h1>";
                    $content .= "<strong>Description</strong>";
                    $content .= "<div class='story-page-description-paragraph'>";
                        $content .= $storyData['dcDescription']; 
                    $content .= "</div>";
                $content .= "</div>";

            //Status Chart
            $content .= "<div id='story-page-right-side' class='storypg-chart'>";

                // Set request parameters for status data
                $url = network_home_url()."/tp-api/completionStatus";
                $requestType = "GET";
            
                // Execude http request
                include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                // Save status data
                $statusTypes = json_decode($result, true);

                $statusCount = array(
                                    "Not Started" => 0,
                                    "Edit" => 0,
                                    "Review" => 0,
                                    "Completed" => 0
                                );
                $itemCount = 0;
                foreach ($storyData['Items'] as $item) {
                    //var_dump($item['CompletionStatusName']);
                    switch ($item['CompletionStatusName']){
                        case 'Not Started':
                            $statusCount['Not Started'] += 1; 
                            break;
                        case 'Edit':
                            $statusCount['Edit'] += 1;
                            break;
                        case 'Review':
                            $statusCount['Review'] += 1;
                            break;
                        case 'Completed':
                            $statusCount['Completed'] += 1;
                            break;
                    }
                    
                    $itemCount += 1;
                }
                echo $statusCount['Not-Started'];
                $content .= "<strong>Transcription status</strong><br />";
                $content .= "<canvas id='statusChart' class='status-main-chart' width='200' height='200'></canvas>";
                $content .= "<script>
                                var ctx = document.getElementById('statusChart');
                                var myDoughnutChart = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels :['Not Started','Edit','Review','Completed'],
                                        datasets: [{
                                            data: [".$statusCount['Not Started'].", ".$statusCount['Edit'].", ".$statusCount['Review'].", ".$statusCount['Completed']."],
                                            backgroundColor: [";
                                                foreach ($statusTypes as $statusType) {
                                                    $content .= '"'.$statusType['ColorCode'].'", ';
                                                }
                $content .=                 "]
                                        }]
                                    },
                                    options: {
                                        tooltips: {
                                          titleFontSize: 10,
                                          bodyFontSize: 10
                                        },
                                        legend : {
                                                    display: false
                                                },
                                        responsive: false
                                    }
                                });
                            </script>";
                            
                            $statusPercentage = array(
                                "Not Started" => intval(($statusCount['Not Started']*100)/$itemCount),
                                "Edit" => intval(($statusCount['Edit']*100)/$itemCount),
                                "Review" => intval(($statusCount['Review']*100)/$itemCount),
                                "Completed" => intval(($statusCount['Completed']*100)/$itemCount)
                            );

                $content .= "<table width=\"100%\">\n";
                                    foreach ($statusTypes as $statusType) {
                                        $content .= "<tr>\n";
                                            $content .= "<td><span class=\"colorbox\" style='background-color: ".$statusType['ColorCode']."; background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, ".$statusType['ColorCode']."), color-stop(1, ".$statusType['ColorCodeGradient']."));'></span></td>\n";
                                            $content .= "<td>".$statusType['Name']."</td>\n";
                                            $content .= "<td class=\"alg_r\">".$statusPercentage[$statusType['Name']]." %</td>\n";
                                        $content .= "</tr>\n";
                                    }
                                $content .= "</table>\n";
    
                            $content .= "<div class=\"facts\">\n";
                            
                                // Set request parameters
                                $url = network_home_url()."/tp-api/fieldMappings";
                                $requestType = "GET";
                            
                                // Execude request
                                include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

                                // Display data
                                $fieldMappings = json_decode($result, true);

                                $fields = array();
                                foreach ($fieldMappings as $fieldMapping) {
                                    $fields[$fieldMapping['Name']] = $fieldMapping['DisplayName'];
                                }
                                foreach ($storyData as $key => $value) {
                                    if ($fields[$key] != null && $fields[$key] != "") {
                                        $content .= "<dl>\n";
                                            $content .= "<dt>";
                                                $content .= $fields[$key];
                                            $content .= "</dt>\n";
                                            $content .= "<dd>";
                                                if (filter_var($value, FILTER_VALIDATE_URL)) {
                                                    $content .= "<a href=\"".$value."\">".$value."</a>";
                                                }
                                                else {
                                                    $content .= $value;
                                                }
                                            $content .= "</dd>\n";
                                        $content .= "</dl>\n";
                                    }
                                }
                            $content .= "</div>\n";

            $content .= "</div>";
            $content .= "<div style='clear:both;'></div>";
    $content .= "</div>";
$content .= "</div>";

    }
    return $content;
}
add_shortcode( 'get_document_data', '_TCT_get_document_data' );
?>