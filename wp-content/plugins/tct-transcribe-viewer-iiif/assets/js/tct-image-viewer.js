jQuery(document).ready(function($){
	"use strict";
	var fullscreenobject;
			
    var $ = jQuery;
    $(document).ready(function(){
		doDocViewer();
	});
	
	
}); 
var iv1;
var ivchanged = false;
var ivchangedid = '';
var ivnext = new Object();
var ivprev = new Object();
function doDocViewer(){
	"use strict";
	
	jQuery('div.tct-image-viewer').each(function(){
		var id = jQuery(this).attr('id');
		//alert(jQuery("#"+id).attr('rel'));
	/*
		iv1 = jQuery("#"+id).iviewer({
			   src: jQuery("#"+id).attr('rel'),
			   update_on_resize: true,
			   zoom_animation: true,
			   mousewheel: true,
			   zoom_min: 20,
			   onMouseMove: function(ev, coords) { },
			   onStartDrag: function(ev, coords) { return true;}, //this image will not be dragged
			   onDrag: function(ev, coords) { }
			});
	*/
// add filter slider
var sliderHtml = '<div class="sliderContainer" id="filterContainer"> ' +
    '  <div class="slidecontainer">' +
    '    <input type="range" min="0" max="300" value="100" class="slider" id="brightnessRange">' +
    '  </div>' +
    '  <div class="slidecontainer">' +
    '    <input type="range" min="1" max="300" value="100" class="slider" id="contrastRange">' +
    '  </div>' +
    '  <div class="slidecontainer">' +
    '    <input type="range" min="1" max="300" value="100" class="slider" id="saturationRange">' +
    '  </div>' +
    '</div>';
jQuery("#"+id).append(sliderHtml);

		iv1 = OpenSeadragon({
		    id: id,
		    sequenceMode: false,
		    showRotationControl: true,
		    prefixUrl: "/wp-content/plugins/tct-transcribe-viewer-iiif/assets/images/osdImages/",
		        tileSources: {
		        type: 'image',
		        url:  jQuery("#"+id).attr('rel'),
		        buildPyramid: false
		    },
		    maxZoomLevel: 8,
		    minZoomLevel: 0.6,
		    autoHideControls: false
		  });
		  iv1.fullPageButton.removeAllHandlers();
		  iv1.fullPageButton.addHandler("click", function () { toggleFullscreen(); });

// filter functionality
  // create Filter overlay button
  function openFilterOverlay() {
    console.log('openfilter overay');
    jQuery('#filterContainer').toggle();
  }

  let filterButton = new OpenSeadragon.Button({
    srcRest: `/wp-content/plugins/tct-transcribe-viewer-iiif/assets/images/osdImages/more_info_norm.png`,
    srcGroup: '/wp-content/plugins/tct-transcribe-viewer-iiif/assets/images/osdImages/more_info_norm.png',
    srcHover: '/wp-content/plugins/tct-transcribe-viewer-iiif/assets/images/osdImages/more_info_hover.png',
    srcDown: '/wp-content/plugins/tct-transcribe-viewer-iiif/assets/images/osdImages/more_info_active.png',
    onClick: openFilterOverlay,
    id: 'filterButton'
  });

  iv1.addControl(filterButton.element, { anchor: OpenSeadragon.ControlAnchor.TOP_LEFT});

  var brightnessSlider = document.getElementById('brightnessRange');
  var contrastSlider = document.getElementById("contrastRange");
  var saturationSlider = document.getElementById("saturationRange");
  //var canvas = document.getElementById("openseadragon1");
  var canvas = document.querySelector(".openseadragon-container canvas");

  var brightness = brightnessSlider.value;
  var contrast = contrastSlider.value;
  var saturation = saturationSlider.value;

  // Update the current slider value (each time you drag the slider handle)
  brightnessSlider.oninput = function() {
    brightness = this.value;
    canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%)";
  }

  contrastSlider.oninput = function() {
    contrast = this.value;
   canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%)";
  }

  saturationSlider.oninput = function() {
    saturation = this.value;
    canvas.style.filter = "brightness(" + brightness + "%) contrast(" + contrast + "%) saturate(" + saturation + "%)";
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

function toggleFullscreen(){
  console.log('asdf');
  let open = (document.fullScreenElement && document.fullScreenElement !== null) ||  (document.mozFullScreen || document.webkitIsFullScreen);
  if(!open) {
    toggleFull(document.getElementById('fsgo'));
    open = true;
    // show edit button when in fullscreen
    //iv1.addControl(fullscreenEditButton.element, { anchor: OpenSeadragon.ControlAnchor.TOP_LEFT});
    // $('#draggableWrapper').show()
  } else {
    toggleFull(document.getElementById('fsgo'));
    open = false;
    // hide edit button when not in fullsceen
    //iv1.removeControl(fullscreenEditButton.element);
    // $('#draggableWrapper').hide();
  }
}

			jQuery('a#an_'+id).each(function(){
				ivnext.postid = jQuery(this).attr('data-rel');
				ivnext.viewerid = id;
				jQuery(this).attr('onclick','getTCTimage(\''+jQuery(this).attr('data-rel')+'\',\''+id+'\',\''+jQuery(this).attr('lang-rel')+'\'); return false;');  
			});
			
			jQuery('a#ap_'+id).each(function(){
				ivprev.postid = jQuery(this).attr('data-rel');
				ivprev.viewerid = id;
				jQuery(this).attr('onclick','getTCTimage(\''+jQuery(this).attr('data-rel')+'\',\''+id+'\',\''+jQuery(this).attr('lang-rel')+'\'); return false;'); 
			});
			
		});
		jQuery('div#transscriber-huge').each(function(){
			//$(this).html('<div class="dragbar">Edit transcription:</div>');
			jQuery(this).draggable({ handle: "div.dragbar" });
			jQuery(this).resizable({alsoResize: "#huge-transcibe-area"});
		});
}

function getTCTimage(pID,vID,lang){
	var isInFullScreen = (document.fullScreenElement && document.fullScreenElement !== null) ||  (document.mozFullScreen || document.webkitIsFullScreen);
	if(isInFullScreen){
		var ask = 0;
		if (jQuery('input#doc_changes').length){
			if(parseFloat(jQuery('input#doc_changes').val())>0){
				ask++;
			}
		}
		if(ask>0 && confirm("It seems, that you did not save your latest changes.\nIf you proceed, these changes will be lost!")){
			if (jQuery('input#doc_changes').length){
				jQuery('input#doc_changes').val('0');	
			}
			unlockDocument();
			jQuery('input#tct-transID').val(pID);
			jQuery.post("/wp-content/themes/transcribathon/admin/inc/custom_skripts/transcribe-skript.php", {'q':'gtimg','postid':pID,'viewer':vID,'lang':lang}, function(res) {
				//alert(JSON.stringify(res)); 
				if(res.status == "ok"){
					//jQuery("#"+vID).iviewer('loadImage', res.imageurl);
					iv1.open({
					        type: 'image',
					        url:  res.imageurl,
		       				buildPyramid: false
		    			});
					if(res.nextlink !== ""){
						jQuery('a#an_'+vID).attr('onclick',res.nextlink+'; return false;').show();
					}else{
						jQuery('a#an_'+vID).hide();	
					}
					if(res.prevlink !== ""){
						jQuery('a#ap_'+vID).attr('onclick',res.prevlink+'; return false;').show();
					}else{
						jQuery('a#ap_'+vID).hide();	
					}
					if(res.tctext){
						jQuery('div#huge-transcibe-area').html(res.tctext);	
						ivchanged = true;
					}else{
						jQuery('div#huge-transcibe-area').html('');	
						ivchanged = true;
					}
					if(res.storyid){
						jQuery('input#tct-story').val(res.storyid);
					}
					if(res.itemid){
						jQuery('input#tct-item').val(res.itemid);
					}
					if(res.islocked == "1"){
						jQuery('div#fs_editor_toggle').removeClass('no').removeClass('locked').addClass('locked');
						// If editor is visible: hide it
						jQuery('div#transscriber-huge').hide();
					}else{
						jQuery('div#fs_editor_toggle').removeClass('no').removeClass('locked');
					}
					ivchangedid = pID;
				}else{
					jQuery('div#transscriber-huge').hide();
				}
			});
		}else if(ask<1){
			if (jQuery('input#doc_changes').length){
				jQuery('input#doc_changes').val('0');	
			}
			unlockDocument();
			jQuery('input#tct-transID').val(pID);
			jQuery.post("/wp-content/themes/transcribathon/admin/inc/custom_skripts/transcribe-skript.php", {'q':'gtimg','postid':pID,'viewer':vID,'lang':lang}, function(res) {
				//alert(JSON.stringify(res));
				if(res.status == "ok"){
					//jQuery("#"+vID).iviewer('loadImage', res.imageurl);
					iv1.open({
					        type: 'image',
					        url:  res.imageurl,
		       				buildPyramid: false
		    			});
					if(res.nextlink !== ""){
						jQuery('a#an_'+vID).attr('onclick',res.nextlink+'; return false;').show();
					}else{
						jQuery('a#an_'+vID).hide();	
					}
					if(res.prevlink !== ""){
						jQuery('a#ap_'+vID).attr('onclick',res.prevlink+'; return false;').show();
					}else{
						jQuery('a#ap_'+vID).hide();	
					}
					if(res.tctext){
						jQuery('div#huge-transcibe-area').html(res.tctext);	
						ivchanged = true;
					}else{
						jQuery('div#huge-transcibe-area').html('');	
						ivchanged = true;
					}
					if(res.storyid){
						jQuery('input#tct-story').val(res.storyid);
					}
					if(res.itemid){
						jQuery('input#tct-item').val(res.itemid);
					}
					if(res.islocked == "1"){
						jQuery('div#fs_editor_toggle').removeClass('no').removeClass('locked').addClass('locked');
						// If editor is visible: hide it
						jQuery('div#transscriber-huge').hide();
					}else{
						jQuery('div#fs_editor_toggle').removeClass('no').removeClass('locked');
					}
					ivchangedid = pID;
				}else{
					jQuery('div#transscriber-huge').hide();
				}
			});
		}
		
	}else{
		document.location.href = '/?p='+pID;
	}
}

