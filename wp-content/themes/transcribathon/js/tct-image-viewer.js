var tct_viewer = (function($, document, window) {
	var osdViewer,
			osdViewerFS,
			imageData,
			imageLink,
			imageHeight,
			imageWidth,
			sliderHtml = '<div class="sliderContainer" id="filterContainer"> ' +
								    '  <div class="slidecontainer">' +
								    '    <div id="brightnessIcon" class="sliderIcon"></div>' +
								    '    <input type="range" min="-100" max="100" value="0" class="iiifSlider" id="brightnessRange">' +
								    '    <div id="brightnessValue" class="sliderValue">0</div>' +
								    '  </div>' +
								    '  <div class="slidecontainer">' +
								    '    <div id="contrastIcon" class="sliderIcon"></div>' +
								    '    <input type="range" min="-100" max="100" value="0" class="iiifSlider" id="contrastRange">' +
								    '    <div id="contrastValue" class="sliderValue">0</div>' +
								    '  </div>' +
										'  <div class="slidecontainer">' +
								    '    <div id="saturationIcon" class="sliderIcon"></div>' +
								    '    <input type="range" min="-100" max="100" value="0" class="iiifSlider" id="saturationRange">' +
								    '    <div id="saturationValue" class="sliderValue">0</div>' +
								    '  </div>' +
										'  <div class="slidecontainer">' +
										    '    <div id="inverteIcon" class="sliderIcon">invert</div>' +
										    '    <input type="checkbox" class="iiifCheckbox" id="invertRange">' +
										    '  </div>' +
								    '  <div id="filterReset"><div class="resetText">Reset to default</div></div>' +
								    '</div>';
	var init = function() {
		addImageFilter();
		getManifestUrl();
		initTiny();
		// in viewer button functionality
		jQuery('#full-page').click(function() {
			toggleFS();
		});

		jQuery('#full-width').click(function() {
			fullWidth();
		});

		jQuery('#filterButton').click(function() {
			openFilterOverlay('');
		});

		jQuery('#full-pageFS').click(function() {
			toggleFS();
		});

		jQuery('#full-widthFS').click(function() {
			fullWidthFS();
		});

		jQuery('#filterButtonFS').click(function() {
			openFilterOverlay('FS');
		});
	},
	addImageFilter = function() {
		jQuery("#openseadragon").append(sliderHtml);
		jQuery("#openseadragonFS").append(sliderHtml);
	},
	getManifestUrl = function() {
		jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
		    'type': 'GET',
		    'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + getUrlParameter('item')
			}, function(response) {
			var response = JSON.parse(response);
			if (response.code == "200") {
	      imageData = JSON.parse(JSON.parse(response.content)[0]['ImageLink']);
	      imageLink = imageData['service']['@id'];
	      imageHeight = imageData['height'];
	      imageWidth = imageData['width'];
				initViewers();
			}
		});
	},
	initViewers = function() {
		osdViewer = OpenSeadragon({
			id: "openseadragon",
			sequenceMode: false,
			showRotationControl: true,
			showFullPageControl: false,
			toolbar: "buttons",
			homeButton: "home",
			zoomInButton: "zoom-in",
			zoomOutButton: "zoom-out",
			rotateLeftButton: "rotate-left",
			rotateRightButton: "rotate-right",
			prefixUrl: "/wp-content/themes/transcribathon/images/osdImages/",
			tileSources: {
			"@context": "http://iiif.io/api/image/2/context.json",
			"@id": imageLink,
			"height": imageHeight,
			"width": imageWidth,
			"profile": [
				"http://iiif.io/api/image/2/level2.json"
			],
			"protocol": "http://iiif.io/api/image"
		},
			maxZoomLevel: 8,
			minZoomLevel: 0.3,
			autoHideControls: false
		});

		osdViewerFS = OpenSeadragon({
			id: "openseadragonFS",
			sequenceMode: false,
			showRotationControl: true,
			showFullPageControl: false,
			toolbar: "buttonsFS",
			homeButton: "homeFS",
			zoomInButton: "zoom-inFS",
			zoomOutButton: "zoom-outFS",
			rotateLeftButton: "rotate-leftFS",
			rotateRightButton: "rotate-rightFS",
			prefixUrl: "/wp-content/themes/transcribathon/images/osdImages/",
			tileSources: {
			"@context": "http://iiif.io/api/image/2/context.json",
			"@id": imageLink,
			"height": imageHeight,
			"width": imageWidth,
			"profile": [
				"http://iiif.io/api/image/2/level2.json"
			],
			"protocol": "http://iiif.io/api/image"
		},
			maxZoomLevel: 8,
			minZoomLevel: 0.3,
			autoHideControls: false,
			preserveImageSizeOnResize: true
		});
		sliderInit();
		osdViewerFS.addOnceHandler('resize', function() {
		window.setTimeout(function() {
			var oldBounds = osdViewerFS.viewport.getBounds();
		  var newBounds = new OpenSeadragon.Rect(0, 0, 1, oldBounds.height / oldBounds.width);
		  osdViewerFS.viewport.fitBounds(newBounds, true);}, 20)
		});
	},
	fullWidth = function() {
	  var oldBounds = osdViewer.viewport.getBounds();
	  var newBounds = new OpenSeadragon.Rect(0, 0, 1, oldBounds.height / oldBounds.width);
	  osdViewer.viewport.fitBounds(newBounds, false);
	},
	fullWidthFS = function() {
	  var oldBounds = osdViewerFS.viewport.getBounds();
	  var newBounds = new OpenSeadragon.Rect(0, 0, 1, oldBounds.height / oldBounds.width);
	  osdViewerFS.viewport.fitBounds(newBounds, false);
	},
	toggleFS = function() {
		switchItemPageView();
	},
	// filter functionality
  // create Filter overlay button
  openFilterOverlay = function(sel) {
    jQuery('#openseadragon' + sel + ' #filterContainer').toggle();
  },
	sliderInit = function(sel) {
	  var brightnessSlider = jQuery('#openseadragon #brightnessRange')[0];
	  var contrastSlider = jQuery('#openseadragon #contrastRange')[0];
	  var saturationSlider = jQuery('#openseadragon #saturationRange')[0];
		var invertSlider = jQuery('#openseadragon #invertRange')[0];
	  var canvas = jQuery('#openseadragon .openseadragon-container canvas')[0];

		var brightnessSliderFS = jQuery('#openseadragonFS #brightnessRange')[0];
		var contrastSliderFS = jQuery('#openseadragonFS #contrastRange')[0];
		var saturationSliderFS = jQuery('#openseadragonFS #saturationRange')[0];
		var invertSliderFS = jQuery('#openseadragonFS #invertRange')[0];
		var canvasFS = jQuery('#openseadragonFS .openseadragon-container canvas')[0];

	  var brightness = Number(brightnessSlider.value) + 100;
	  var contrast = (Number(contrastSlider.value) + 100) * 1;
	  var saturation = (Number(saturationSlider.value) + 100) * 1;
		var invert = 0;

	  var bValue = jQuery('#openseadragon #brightnessValue')[0];
	  var cValue = jQuery('#openseadragon #contrastValue')[0];
	  var sValue = jQuery('#openseadragon #saturationValue')[0];

		var bValueFS = jQuery('#openseadragonFS #brightnessValue')[0];
		var cValueFS = jQuery('#openseadragonFS #contrastValue')[0];
		var sValueFS = jQuery('#openseadragonFS #saturationValue')[0];

	  // Update the current slider value (each time you drag the slider handle)
	  brightnessSlider.oninput = function() {
	    brightness = Number(this.value) + 100;
	    canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			canvasFS.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
	    bValue.innerHTML = brightness - 100;
			bValueFS.innerHTML = brightness - 100;
			brightnessSliderFS.value = this.value;
	  }

	  contrastSlider.oninput = function() {
	    contrast = (Number(this.value) + 100) * 1;
	    canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			canvasFS.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
	    cValue.innerHTML = Number(this.value);
			cValueFS.innerHTML = Number(this.value);
			contrastSliderFS.value = this.value;
	  }

	  saturationSlider.oninput = function() {
	    saturation = (Number(this.value) + 100) * 1;
	    canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			canvasFS.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
	    sValue.innerHTML = Number(this.value);
			sValueFS.innerHTML = Number(this.value);
			saturationSliderFS.value = this.value;
	  }

		invertSlider.oninput = function() {
			if(this.checked) {
				invert = 1;
				invertSliderFS.checked = true;
			} else {
				invert = 0;
				invertSliderFS.checked = false;
			}
			canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			canvasFS.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
		}

		invertSliderFS.oninput = function() {
			if(this.checked) {
				invert = 1;
				invertSlider.checked = true;
			} else {
				invert = 0;
				invertSlider.checked = false;
			}
			canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			canvasFS.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
		}

		// Update the current slider value (each time you drag the slider handle)
		brightnessSliderFS.oninput = function() {
			brightness = Number(this.value) + 100;
			canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			canvasFS.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			bValue.innerHTML = brightness - 100;
			bValueFS.innerHTML = brightness - 100;
			brightnessSlider.value = this.value;
		}

		contrastSliderFS.oninput = function() {
			contrast = (Number(this.value) + 100) * 1;
			canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			canvasFS.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			cValue.innerHTML = Number(this.value);
			cValueFS.innerHTML = Number(this.value);
			contrastSlider.value = this.value;
		}

		saturationSliderFS.oninput = function() {
			saturation = (Number(this.value) + 100) * 1;
			canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			canvasFS.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			sValue.innerHTML = Number(this.value);
			sValueFS.innerHTML = Number(this.value);
			saturationSlider.value = this.value;
		}

		invertSliderFS.oninput = function() {
			if(this.checked) {
				invert = 1;
				invertSlider.checked = true;
			} else {
				invert = 0;
				invertSlider.checked = false;
			}
			canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
			canvasFS.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%) invert(" + invert + ")";
		}

		jQuery('#openseadragon #filterReset').click(function() {
			canvas.style.filter = "brightness(" + 100 + "%) contrast(" + 100 + "%) saturate(" + 100 + "%) invert(0)";
			sValue.innerHTML = 0;
			cValue.innerHTML = 0;
			bValue.innerHTML = 0;
			brightnessSlider.value = 0;
			contrastSlider.value = 0;
		 	saturationSlider.value = 0;
			invertSlider.checked = false;
			canvasFS.style.filter = "brightness(" + 100 + "%) contrast(" + 100 + "%) saturate(" + 100 + "%) invert(0)";
			sValueFS.innerHTML = 0;
			cValueFS.innerHTML = 0;
			bValueFS.innerHTML = 0;
			brightnessSliderFS.value = 0;
			contrastSliderFS.value = 0;
			saturationSliderFS.value = 0;
			invertSliderFS.checked = false;
			brightness = 100;
			contrast = 100;
			saturation = 100;
		});

		jQuery('#openseadragonFS #filterReset').click(function() {
			canvas.style.filter = "brightness(" + 100 + "%) contrast(" + 100 + "%) saturate(" + 100 + "%) invert(0)";
			sValue.innerHTML = 0;
			cValue.innerHTML = 0;
			bValue.innerHTML = 0;
			brightnessSlider.value = 0;
			contrastSlider.value = 0;
			saturationSlider.value = 0;
			invertSlider.checked = false;
			canvasFS.style.filter = "brightness(" + 100 + "%) contrast(" + 100 + "%) saturate(" + 100 + "%) invert(0)";
			sValueFS.innerHTML = 0;
			cValueFS.innerHTML = 0;
			bValueFS.innerHTML = 0;
			brightnessSliderFS.value = 0;
			contrastSliderFS.value = 0;
			saturationSliderFS.value = 0;
			invertSliderFS.checked = false;
			brightness = 100;
			contrast = 100;
			saturation = 100;
		});
	},
	fullscreenEdit = function() {
	  console.log('show fullscreen Editor');
	  toggleFSEditor('fs_editor_toggle');
	},
	initTiny = function() {
		//none fs ones
		initTinyWithConfig('#full-view-editor #item-page-transcription-text');
		initTinyWithConfig('#full-view-editor #item-page-description-text');
	},
	getUrlParameter = function(sParam) {
		var sPageURL = window.location.search.substring(1),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
			}
		}
	},
	initTinyWithConfig = function(selector) {
	  tinymce.init({
	    selector: selector,
	    inline: true,
	    height:120,
	    plugins: ['charmap','paste'],
	    toolbar: 'bold italic underline strikethrough removeformat | alignleft aligncenter alignright | missbut unsure position-in-doc',
	    menubar: true,
	    browser_spellcheck: true,
	    paste_auto_cleanup_on_paste : true,
	    body_id: 'htranscriptor',
	    setup: function (editor) {
	      editor.ui.registry.addButton('missbut', {
	        title: 'Insert an indicator for missing text',
	        text: '',
	        icon: 'missing',
	        onAction: function () {
	          editor.insertContent('<span style=\"display:inline;\" class=\"tct_missing\" alt=\"missing\"> MISSING </span>');
	        }
	      });
	      editor.ui.registry.addButton('unsure', {
	        title: 'Mark selected as unclear',
	        text: '',
	        icon: 'unsure',
	        onAction: function () {
	          if(editor.selection.getContent({format : 'text'}).split(' ').join('').length < 1){
	            editor.insertContent('<span class=\"tct-uncertain\"> ...</span>')
	          }else{
	            if (editor.selection.getStart().className == "tct-uncertain") {
	              var node = editor.selection.getStart();
	              node.parentNode.replaceChild(document.createTextNode(node.innerHTML.replace("&nbsp;", "")), node);
	            }
	            else if (editor.selection.getEnd().className == "tct-uncertain"){
	              var node = editor.selection.getEnd();
	              node.parentNode.replaceChild(document.createTextNode(node.innerHTML.replace("&nbsp;", "")), node);
	            }
	            else{
	              editor.insertContent('<span class=\"tct-uncertain\">'+editor.selection.getContent({format : 'html'})+'</span>');
	            }
	          }
	        }
	      });
	    },
	    style_formats: [
	    {title: 'unclear, please review', inline: 'span', classes: 'tct_unclear'},
	    {title: 'Note', inline: 'span', classes: 'tct_note'},
	    {title: 'Badge', inline: 'span', styles: { display: 'inline-block', border: '1px solid #2276d2', 'border-radius': '5px', padding: '2px 5px', margin: '0 2px', color: '#2276d2' }}
	    ],
	    formats: {
	    alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'left' },
	    aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'center' },
	    alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'right' },
	    alignfull: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'full' },
	    bold: { inline: 'span', 'classes': 'bold' },
	    italic: { inline: 'span', 'classes': 'italic' },
	    underline: { inline: 'span', 'classes': 'underline', exact: true },
	    strikethrough: { inline: 'del' },
	    },
	  })
	};
	$(document).ready(function($) {
		init();
	});
	return {
		initTinyWithConfig: initTinyWithConfig
	}
})(jQuery, document, window);
