jQuery(document).ready(function($){
	"use strict";
	var fullscreenobject;

    var $ = jQuery;
    $(document).ready(function(){
		doDocViewer();
		console.log('in createViewer');
		initTiny();
	});


});
var iv1;
var iv1FS;
var ivchanged = false;
var ivchangedid = '';
var ivnext = new Object();
var ivprev = new Object();
function doDocViewer(){
	"use strict";

	jQuery('div#openseadragon').each(function(){
		var id = jQuery(this).attr('id');
// add filter slider
var sliderHtml = '<div class="sliderContainer" id="filterContainer"> ' +
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
    '  <div id="filterReset"><div class="resetText">Reset to default</div></div>' +
    '</div>';
jQuery("#"+id).append(sliderHtml);
jQuery("#openseadragonFS").append(sliderHtml);
		var iiifImageSrc =
		iv1 = OpenSeadragon({
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
			  "@id": "https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000161",
			  "height": 8388,
			  "width": 5479,
			  "profile": [
			    "http://iiif.io/api/image/2/level2.json"
			  ],
			  "protocol": "http://iiif.io/api/image"
		  },
		    maxZoomLevel: 8,
		    minZoomLevel: 0.3,
		    autoHideControls: false
		  });

// fullscreen viewer TODO, not sure if both should be available at the same time  openseadragonFS
iv1FS = OpenSeadragon({
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
			  "@id": "https://iiif.onb.ac.at/images/ANNO/fug15840321/z116567901_00000161",
			  "height": 8388,
			  "width": 5479,
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

// add fullwidth button  toggleFS     fullWidth    fullWidthFS   openFilterOverlay
jQuery('#full-page').click(function() {
	toggleFS();
});

jQuery('#full-width').click(function() {
	fullWidth();
});

jQuery('#filterButton').click(function() {
	openFilterOverlay('');
});
/*
jQuery('#filterButtonFS').click(function(event) {
	switchItemTab(event, "settings-tab");
	//TODO: remove dirty hack
	jQuery('.tablinks .fa-sliders-h')[0].parentElement.className += " active";
});
*/
jQuery('#full-pageFS').click(function() {
	toggleFS();
});

jQuery('#full-widthFS').click(function() {
	fullWidthFS();
});

jQuery('#filterButtonFS').click(function() {
	openFilterOverlay('FS');
});

sliderInit('');
sliderInit('FS');

function fullWidth() {
  var oldBounds = iv1.viewport.getBounds();
  var newBounds = new OpenSeadragon.Rect(0, 0, 1, oldBounds.height / oldBounds.width);
  iv1.viewport.fitBounds(newBounds, false);
}

function fullWidthFS() {
  var oldBounds = iv1FS.viewport.getBounds();
  var newBounds = new OpenSeadragon.Rect(0, 0, 1, oldBounds.height / oldBounds.width);
  iv1FS.viewport.fitBounds(newBounds, false);
}

iv1FS.addOnceHandler('resize', function() {
window.setTimeout(function() {console.log('asdfasfasdfsdfsdafasdfdasfsdf');var oldBounds = iv1FS.viewport.getBounds();
  var newBounds = new OpenSeadragon.Rect(0, 0, 1, oldBounds.height / oldBounds.width);
  iv1FS.viewport.fitBounds(newBounds, true);}, 20)
});

function toggleFS() {
	switchItemPageView();
}

// filter functionality
  // create Filter overlay button
  function openFilterOverlay(sel) {
    jQuery('#openseadragon' + sel + ' #filterContainer').toggle();
  }

	function sliderInit(sel) {
	  var brightnessSlider = jQuery('#openseadragon' + sel + ' #brightnessRange')[0];
	  var contrastSlider = jQuery('#openseadragon' + sel + ' #contrastRange')[0];
	  var saturationSlider = jQuery('#openseadragon' + sel + ' #saturationRange')[0];
	  //var canvas = document.getElementById("openseadragon1");
	  var canvas = jQuery('#openseadragon' + sel + ' .openseadragon-container canvas')[0];

	  var brightness = Number(brightnessSlider.value) + 100;
	  var contrast = (Number(contrastSlider.value) + 100) * 1;
	  var saturation = (Number(saturationSlider.value) + 100) * 1;

	  var bValue = jQuery('#openseadragon' + sel + ' #brightnessValue')[0];
	  var cValue = jQuery('#openseadragon' + sel + ' #contrastValue')[0];
	  var sValue = jQuery('#openseadragon' + sel + ' #saturationValue')[0];

	  // Update the current slider value (each time you drag the slider handle)
	  brightnessSlider.oninput = function() {
	    brightness = Number(this.value) + 100;
	    canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%)";
	    bValue.innerHTML = brightness - 100;
	  }

	  contrastSlider.oninput = function() {
	    contrast = (Number(this.value) + 100) * 1;
	    canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%)";
	    cValue.innerHTML = Number(this.value);
	  }

	  saturationSlider.oninput = function() {
	    saturation = (Number(this.value) + 100) * 1;
	    canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%)";
	    sValue.innerHTML = Number(this.value);
	  }

		jQuery('#openseadragon' + sel + ' #filterReset').click(function() {
			canvas.style.filter = "brightness(" + 100 + "%) contrast(" + 100 + "%) saturate(" + 100 + "%)";
			sValue.innerHTML = 0;
			cValue.innerHTML = 0;
			bValue.innerHTML = 0;
			brightnessSlider.value = 0;
			contrastSlider.value = 0;
		 	saturationSlider.value = 0;
		});
	}


function fullscreenEdit() {
  console.log('show fullscreen Editor');
  toggleFSEditor('fs_editor_toggle');
}

let fullscreenEditButton = new OpenSeadragon.Button({
  srcRest: `https://cdnjs.cloudflare.com/ajax/libs/openseadragon/2.4.0/images/fullpage_hover.png`,
  srcGroup: 'https://cdnjs.cloudflare.com/ajax/libs/openseadragon/2.4.0/images/fullpage_hover.png',
  srcHover: 'https://cdnjs.cloudflare.com/ajax/libs/openseadragon/2.4.0/images/fullpage_hover.png',
  srcDown: 'https://cdnjs.cloudflare.com/ajax/libs/openseadragon/2.4.0/images/fullpage_hover.png',
  onClick: fullscreenEdit,
  id: 'fullScreenEditButton'
});
});
}

function initTiny() {
	console.log(jQuery('#item-page-transcription-text'));
	/*tinyMCE.init({
		selector: '#item-page-transcription-text',
		inline: true
	});
	*/
}
