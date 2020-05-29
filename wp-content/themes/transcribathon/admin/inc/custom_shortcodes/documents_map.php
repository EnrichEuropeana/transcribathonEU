<?php
// get Document data from API
function _TCT_documents_map( $atts ) {   
    //include theme directory for text hovering
    $theme_sets = get_theme_mods();

    // Build Story page content
    $content = "<div id='documentsMap'></div>";
   
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
							      container: 'documentsMap',
							      style: 'mapbox://styles/fandf/cjnpzoia60m4y2rp5cvoq9t8z',
							      center: [62.8, -21],
							      zoom: 1
							    });
							map.addControl(new mapboxgl.NavigationControl());
							
								fetch('/dev/tp-api/places')
							                        .then(function(response) {
							                          return response.json();
							                        })
							                        .then(function(places) {
							                          console.log(places);
										    var geojson = {type: 'FeatureCollection', features: [] }
										    for(var i = 0; i < places.length; i++) {
										      geojson.features.push({
										        type: 'Feature',
										        properties: {
										          id: places[i].PlaceId,
										          name: places[i].Name,
											  comment: places[i].Comment,
											  itemId: places[i].ItemId,
											  storyId: places[i].StoryId
										        },
										        geometry: {
										          type: 'Point',
										          coordinates: [places[i].Latitude, places[i].Longitude]
										        }
										      })
										}
							                   	map.on('load', function() {
											map.loadImage('/dev/wp-content/themes/transcribathon/images/map-marker.png', function(err, icon) {
												if(err) throw err;
												map.addImage('icon', icon);
	
											      map.addSource('all-places', {
											        type: 'geojson',
											        data: geojson,
											        cluster: true,
											        clusterMaxZoom: 14,
											        clusterRadius: 65
											      });
											      map.addLayer({
											        id: 'clusters',
											        type: 'circle',
											        source: 'all-places',
											        filter: ['has', 'point_count'],
											        paint: {
											          'circle-color': [
											            'step',
											            ['get', 'point_count'],
											            '#56a6c4',
											            12,
											            '#7bbbd2',
											            90,
											            '#9bcadb',
											            220,
											            '#b7dcea'
											            ],
											          'circle-radius': [
											            'step',
											            ['get', 'point_count'],
											            20,
											            12,
											            25,
											            90,
											            30,
											            220,
											            36
											          ]
											        }
											      })
											      map.addLayer({
											        id: 'cluster-count',
											        type: 'symbol',
											        source: 'all-places',
											        filter: ['has', 'point_count'],
											        layout: {
											          'text-field': '{point_count_abbreviated}',
											          'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
											          'text-size': 12
											        }
											      });
											
											      map.addLayer({
											        id: 'unclustered-point',
											        type: 'symbol',
											        source: 'all-places',
											        filter: ['!', ['has', 'point_count']],
											        layout: {
													'icon-image': 'icon',
													'icon-size': 1
												}
											      });
										
											        map.on('click', 'unclustered-point', function (e) {
											         	console.log(e);
													var features = map.queryRenderedFeatures(e.point, { layers: ['unclustered-point'] });
													console.log(features);
											        	var name = features[0].properties.name;
													var description = features[0].properties.comment;
													var itemId = features[0].properties.itemId;
													var popup = new mapboxgl.Popup({closeOnClick: false})
														.setLngLat(e.lngLat)
														.setHTML('<div class=\"popupWrapper\"><div class=\"name\"><a href=\"https://europeana.fresenia.man.poznan.pl/dev/documents/story/item/?item=' + itemId + '\">' + name + '</div><div class=\"comment\">' + description + '</div></div>')
														.addTo(map);
												});

											      map.on('click', 'clusters', function (e) {
											        console.log('adsfasdfhsd');
											        var features = map.queryRenderedFeatures(e.point, { layers: ['clusters'] });
											        var clusterId = features[0].properties.cluster_id;
											        map.getSource('all-places').getClusterExpansionZoom(clusterId, function (err, zoom) {
											          if (err)
											          return;
											
											          map.easeTo({
											            center: features[0].geometry.coordinates,
											            zoom: zoom
											          });
											        });
											      });
											
											      map.on('mouseenter', 'clusters', function () {
											        map.getCanvas().style.cursor = 'pointer';
											      });
											      map.on('mouseleave', 'clusters', function () {
											        map.getCanvas().style.cursor = '';
										      });
									});
    });
                      						  });
							  
						});
    						</script>";

    return $content;
}
add_shortcode( 'documents_map', '_TCT_documents_map' );
?>