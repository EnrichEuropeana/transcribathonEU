<?php

// include required files
include($_SERVER["DOCUMENT_ROOT"].'/wp-load.php');

// get Document data from API
function _TCT_get_document_data( $atts ) {   
    //include theme directory for text hovering
    $theme_sets = get_theme_mods();

    // Build Story page content
    $content = "";
    $content = "<style>
                  
                .story-page-slider:hover button.slick-prev.slick-arrow {
                    background: ".$theme_sets['vantage_general_link_color']." !important;
                    color: #ffffff;
                }
                
                .story-page-slider:hover button.slick-next.slick-arrow {
                    background: ".$theme_sets['vantage_general_link_color']." !important;
                    color: #ffffff;
                }

            </style>";
    if (isset($_GET['story']) && $_GET['story'] != "") {
        // get Story Id from url parameter
        $storyId = $_GET['story'];

        // Set request parameters
        $url = home_url()."/tp-api/stories/".$storyId;
        $requestType = "GET";
    
        // Execude request
        include dirname(__FILE__)."/../custom_scripts/send_api_request.php";

        // Display data
        $storyData = json_decode($result, true);
        $storyData = $storyData[0];

        // Top image slider 
        $content .= "<div class='story-page-slider'>";
            $i = 0;
            foreach ($storyData['Items'] as $item) {
                $image = json_decode($item['ImageLink'], true);
                if (substr($image['service']['@id'], 0, 4) == "http") {
                    $imageLink = $image['service']['@id'];
                }
                else {
                    $imageLink = "http://".$image['service']['@id'];
                }

                if ($image["width"] != null || $image["height"] != null) {
                    if ($image["width"] <= $image["height"]) {
                        $imageLink .= "/0,0,".$image["width"].",".$image["width"];
                    }
                    else {
                        $imageLink .= "/0,0,".$image["height"].",".$image["height"];
                    }
                }
                else {
                    $imageLink .= "/full";
                }
                $imageLink .= "/250,250/0/default.jpg";
/*
                $image = json_decode($item['ImageLink'], true);
                $imageLink = $image['service']['@id'];
                if ($image["width"] <= $image["height"]) {
                    $imageLink .= "/0,0,".$image["width"].",".$image["width"];
                }
                else {
                    $imageLink .= "/0,0,".$image["height"].",".$image["height"];
                }
                $imageLink .= "/250,250/0/default.jpg";
                */
                $content .= "<a href='".home_url()."/documents/story/item?story=".$storyData['StoryId']."&item=".$item['ItemId']."'>";
                    $content .= "<div class='label-img-status shadow-img-corner'></div>";
                    $content .= "<div class='label-img-status' 
                                    style='border-color: ".$item['CompletionStatusColorCode']." transparent transparent ".$item['CompletionStatusColorCode']."'></div>";
                    $content .= "<div class='image-numbering theme-color-background'>";
                        $content .= ($i + 1);
                    $content .= "</div>";
                    $content .= "<img data-lazy='".$imageLink."'>";
                $content .= "</a>";
                $i++;
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
                    slidesToShow: 9,
                    slidesToScroll: 9,
                    lazyLoad: 'ondemand',
                    responsive: [
                        {
                            breakpoint: 1900,
                            settings: {
                            slidesToShow: 8,
                            slidesToScroll: 8
                            }
                        },
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
                $content .= '<li><a href="'.home_url().'/documents/" style="text-decoration: none;">Stories</a></li>';
                $content .= '<li><i class="fal fa-angle-right"></i></li>';
                $content .= '<li>';
                $content .= $storyData['dcTitle'];
                $content .= '</li>';
            $content .= '</ul>';
            /*
                $content .= '<ul class="story-navigation-content-container right" style="">
                            <li class="rgt"><a title="next" href=""><i class="fal fa-angle-right" style="font-size: 20px;"></i></a></li>
                        </ul>';
            */
        $content .= '</div>';

$content .= "<div id='total-storypg' class='storypg-container'>";
    $content .= "<div class='main-storypg'>";
                $content .= "<div class='storypg-info'>";
                    $storyTitle = explode(" || ", $storyData['dcTitle']);
                    foreach ($storyTitle as $singleTitle) {
                        $content .= "<h1 class='storypg-title'>";
                            $content .= $singleTitle;
                        $content .= "</h1>";
                    }

                    $content .= "<strong style='text-transform: capitalize;'>Description</strong>";
                    $storyDescription = explode(" || ", $storyData['dcDescription']);
                    foreach ($storyDescription as $singleDescription) {
                        $content .= "<div class='story-page-description-paragraph'>";
                            $content .= $singleDescription; 
                        $content .= "</div>";
                    }
                $content .= "</div>";

            //Status Chart
            $content .= "<div id='story-page-right-side' class='storypg-chart'>";

                // Set request parameters for status data
                $url = home_url()."/tp-api/completionStatus";
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
                                "Not Started" => round(($statusCount['Not Started']*100)/$itemCount),
                                "Edit" => round(($statusCount['Edit']*100)/$itemCount),
                                "Review" => round(($statusCount['Review']*100)/$itemCount),
                                "Completed" => round(($statusCount['Completed']*100)/$itemCount)
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
                                $url = home_url()."/tp-api/fieldMappings";
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
                                                $valueList = explode(" || ", $value);
                                                $valueList = array_unique($valueList);
                                                $i = 0;
                                                foreach ($valueList as $singleValue) {
                                                    if ($singleValue != "") {
                                                        if ($i == 0) {
                                                            if (filter_var($singleValue, FILTER_VALIDATE_URL)) {
                                                                $content .= "<a target=\"_blank\" href=\"".$singleValue."\">".$singleValue."</a>";
                                                            }
                                                            else {
                                                                $content .= $singleValue;
                                                            }
                                                        }
                                                        else {
                                                            if (filter_var($singleValue, FILTER_VALIDATE_URL)) {
                                                                $content .= "</br>";
                                                                $content .= "<a target=\"_blank\" href=\"".$singleValue."\">".$singleValue."</a>";
                                                            }
                                                            else {
                                                                $content .= "</br>";
                                                                $content .= $singleValue;
                                                            }
                                                        }
                                                    }
                                                    $i += 1;
                                                }
                                            $content .= "</dd>\n";
                                        $content .= "</dl>\n";
                                    }
                                }
                                $location = "";
                                if ($storyData['PlaceName'] != null && $storyData['PlaceName'] != "") {
                                    $location .= $storyData['PlaceName'];
                                }
                                if ($storyData['PlaceLatitude'] != null && $storyData['PlaceLatitude'] != "" && $storyData['PlaceLongitude'] != null && $storyData['PlaceLongitude'] != "") {
                                    $location .= " (".$storyData['PlaceLatitude'].", ".$storyData['PlaceLongitude'].")";
                                }
                                if ($location != "") {
                                    $content .= "<dl>\n";
                                        $content .= "<dt>";
                                            $content .= "Location: ";
                                        $content .= "</dt>\n";
                                        $content .= "<dd>";
                                            $content .= $location;
                                        $content .= "</dd>\n";
                                    $content .= "</dl>\n";
                                }

                            $content .= "</div>\n";

            $content .= "</div>";
            $content .= "<div style='clear:both;'></div>";
	    $content .= "<div id='storyMap'></div>";
	    $content .= "<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.4.1/mapbox-gl-geocoder.min.js'></script>
						<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.4.1/mapbox-gl-geocoder.css' type='text/css' />";
	    $content .= "    <script>
							jQuery(document).ready(function() {
						        var url_string = window.location.href;
							var url = new URL(url_string);
							var storyId = url.searchParams.get('story');
							var coordinates = jQuery('.location-input-coordinates-container.location-input-container > input ')[0];

							    mapboxgl.accessToken = 'pk.eyJ1IjoiZmFuZGYiLCJhIjoiY2pucHoybmF6MG5uMDN4cGY5dnk4aW80NSJ9.U8roKG6-JV49VZw5ji6YiQ';
							    var map = new mapboxgl.Map({
							      container: 'storyMap',
							      style: 'mapbox://styles/fandf/ck4birror0dyh1dlmd25uhp6y',
							      center: [13, 46],
							      zoom: 2.8
							    });
								var bounds = new mapboxgl.LngLatBounds();
							map.addControl(new mapboxgl.NavigationControl());
							
                            fetch('/tp-api/stories/' + storyId)
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(places) {
                                console.log(places);
                                if(places.length > 0) {
                                    places[0].Items.forEach(function(marker) {
                                        marker.Places.forEach(function(place) {
                                            var el = document.createElement('div');
                                            el.className = 'marker savedMarker';
                                            var popup = new mapboxgl.Popup({offset: 25, closeButton: false})
                                            .setHTML('<div class=\"popupWrapper\"><div class=\"name\">' + (place.Name || \"\") + '</div><div class=\"comment\">' + (place.Comment || \"\") + '</div>' + '<a class=\"item-link\" href=\"' + home_url + '/documents/story/item/?story=' + places[0].StoryId + '&item=' + marker.ItemId + '\">' + marker.Title + '</a></div></div>');
                                            bounds.extend([place.Longitude, place.Latitude]);
                                            new mapboxgl.Marker({element: el, anchor: 'bottom'})
                                            .setLngLat([place.Longitude, place.Latitude])
                                            .setPopup(popup)
                                            .addTo(map);
                                        });
                                    });
                                    // add story location to the map

                                    if (places[0].PlaceLongitude != 0 || places[0].PlaceLongitude != 0) {
                                        var el = document.createElement('div');
                                        el.className = 'marker savedMarker storyMarker';
                                        var popup = new mapboxgl.Popup({offset: 25, closeButton: false})
                                        .setHTML('<div class=\"popupWrapper\"><div class=\"story-location-header\">Story Location</div><div class=\"title\">' + places[0].dcTitle + '</div><div class=\"name\">' + places[0].PlaceName + '</div></div>');
                                        bounds.extend([places[0].PlaceLongitude, places[0].PlaceLatitude]);
        
                                        new mapboxgl.Marker({element: el, anchor: 'bottom'})
                                        .setLngLat([places[0].PlaceLongitude, places[0].PlaceLatitude])
                                        .setPopup(popup)
                                        .addTo(map);

                                        map.fitBounds(bounds, {padding: {top: 50, bottom:20, left: 20, right: 20}});
                                    }
                                }
                            });
							  
						});
    						</script>";
    $content .= "</div>";
$content .= "</div>";

    }
    return $content;
}
add_shortcode( 'get_document_data', '_TCT_get_document_data' );
?>